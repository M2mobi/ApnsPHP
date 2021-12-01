<?php

/**
 * @file
 * Server class definition.
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/apns-php/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to aldo.armiento@gmail.com so we can send you a copy immediately.
 *
 * @author (C) 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * @version $Id$
 */

namespace ApnsPHP\Push;

use ApnsPHP\Exception;
use ApnsPHP\Message;
use ApnsPHP\Push;
use ApnsPHP\Push\Server\Exception as ServerException;

/**
 * The Push Notification Server Provider.
 *
 * The class manages multiple Push Notification Providers and an inter-process message
 * queue. This class is useful to parallelize and speed-up send activities to Apple
 * Push Notification service.
 */
class Server extends Push
{
    /** @var int Main loop sleep time in micro seconds. */
    protected const MAIN_LOOP_USLEEP = 200000;

    /** @var int Shared memory size in bytes useful to store message queues. */
    protected const SHM_SIZE = 524288;

    /** @var int Message queue start identifier for messages.
     * For every process 1 is added to this number. */
    protected const SHM_MESSAGES_QUEUE_KEY_START = 1000;

    /** @var int Message queue identifier for not delivered messages. */
    protected const SHM_ERROR_MESSAGES_QUEUE_KEY = 999;

    /** @var int The number of processes to start. */
    protected $processes = 3;

    /** @var array Array of process PIDs. */
    protected $pids = [];

    /** @var int The parent process id. */
    protected $parentPid;

    /** @var int Cardinal process number (0, 1, 2, ...). */
    protected $currentProcess;

    /** @var int The number of running processes. */
    protected $runningProcesses;

    /** @var resource Shared memory. */
    protected $shm;

    /** @var resource Semaphore. */
    protected $sem;

    /**
     * @param int $environment Environment.
     * @param string $providerCertificateFile Provider certificate file with key (Bundled PEM).
     */
    public function __construct($environment, $providerCertificateFile)
    {
        parent::__construct($environment, $providerCertificateFile);

        $this->parentPid = posix_getpid();
        $this->shm = shm_attach(mt_rand(), self::SHM_SIZE);
        if ($this->shm === false) {
            throw new ServerException(
                'Unable to get shared memory segment'
            );
        }

        $this->sem = sem_get(mt_rand());
        if ($this->sem === false) {
            throw new ServerException(
                'Unable to get semaphore id'
            );
        }

        register_shutdown_function([$this, 'onShutdown']);

        pcntl_signal(SIGCHLD, [$this, 'onChildExited']);
        foreach ([SIGTERM, SIGQUIT, SIGINT] as $signal) {
            pcntl_signal($signal, [$this, 'onSignal']);
        }
    }

    /**
     * Checks if the server is running and calls signal handlers for pending signals.
     *
     * Example:
     * ```
     * while ($Server->run()) {
     *     // do somethings...
     *     usleep(200000);
     * }
     * ```
     *
     * @return bool True if the server is running.
     */
    public function run()
    {
        pcntl_signal_dispatch();
        return $this->runningProcesses > 0;
    }

    /**
     * Waits until a forked process has exited and decreases the current running
     * process number.
     */
    public function onChildExited()
    {
        while (pcntl_waitpid(-1, $status, WNOHANG) > 0) {
            $this->runningProcesses--;
        }
    }

    /**
     * When a child (not the parent) receive a signal of type TERM, QUIT or INT
     * exits from the current process and decreases the current running process number.
     *
     * @param int $signal Signal number.
     */
    public function onSignal($signal)
    {
        switch ($signal) {
            case SIGTERM:
            case SIGQUIT:
            case SIGINT:
                if (($pid = posix_getpid()) != $this->parentPid) {
                    $this->logger()->info("Child $pid received signal #{$signal}, shutdown...");
                    $this->runningProcesses--;
                    exit(0);
                }
                break;
            default:
                $this->logger()->info("Ignored signal #{$signal}.");
                break;
        }
    }

    /**
     * When the parent process exits, cleans shared memory and semaphore.
     *
     * This is called using 'register_shutdown_function' pattern.
     * @see http://php.net/register_shutdown_function
     */
    public function onShutdown()
    {
        if (posix_getpid() == $this->parentPid) {
            $this->logger()->info('Parent shutdown, cleaning memory...');
            @shm_remove($this->shm) && @shm_detach($this->shm);
            @sem_remove($this->sem);
        }
    }

    /**
     * Set the total processes to start, default is 3.
     *
     * @param int $processes Processes to start up.
     */
    public function setProcesses($processes)
    {
        $processes = (int)$processes;
        if ($processes <= 0) {
            return;
        }
        $this->processes = $processes;
    }

    /**
     * Starts the server forking all processes and return immediately.
     *
     * Every forked process is connected to Apple Push Notification Service on start
     * and enter on the main loop.
     */
    public function start()
    {
        for ($i = 0; $i < $this->processes; $i++) {
            $this->currentProcess = $i;
            $this->pids[$i] = $pid = pcntl_fork();
            if ($pid == -1) {
                $this->logger()->warning('Could not fork');
            } elseif ($pid > 0) {
                // Parent process
                $this->logger()->info("Forked process PID {$pid}");
                $this->runningProcesses++;
            } else {
                // Child process
                try {
                    parent::connect();
                } catch (Exception $e) {
                    $this->logger()->error($e->getMessage() . ', exiting...');
                    exit(1);
                }
                $this->mainLoop();
                parent::disconnect();
                exit(0);
            }
        }
    }

    /**
     * Adds a message to the inter-process message queue.
     *
     * Messages are added to the queues in a round-robin fashion starting from the
     * first process to the last.
     *
     * @param Message $message The message.
     */
    public function add(Message $message)
    {
        static $n = 0;
        if ($n >= $this->processes) {
            $n = 0;
        }
        sem_acquire($this->sem);
        $queue = $this->getSHMQueue(self::SHM_MESSAGES_QUEUE_KEY_START, $n);
        $queue[] = $message;
        $this->setSHMQueue(self::SHM_MESSAGES_QUEUE_KEY_START, $n, $queue);
        sem_release($this->sem);
        $n++;
    }

    /**
     * Legacy alias for getMessageQueue()
     *
     * @see getMessageQueue()
     * @deprecated remove in 3.0
     */
    public function getQueue($empty = true)
    {
        $this->logger()->warning('getQueue() is deprecated, use getMessageQueue() instead.');
        return $this->getMessageQueue($empty);
    }

    /**
     * Returns messages in the message queue.
     *
     * When a message is successful sent or reached the maximum retry time is removed
     * from the message queue and inserted in the Errors container. Use the getErrors()
     * method to retrive messages with delivery error(s).
     *
     * @param bool $empty Empty message queue.
     * @return array Array of messages left on the queue.
     */
    public function getMessageQueue($empty = true)
    {
        $messages = [];
        sem_acquire($this->sem);
        for ($i = 0; $i < $this->processes; $i++) {
            $messages = array_merge($messages, $this->getSHMQueue(self::SHM_MESSAGES_QUEUE_KEY_START, $i));
            if ($empty) {
                $this->setSHMQueue(self::SHM_MESSAGES_QUEUE_KEY_START, $i);
            }
        }
        sem_release($this->sem);
        return $messages;
    }

    /**
     * Returns messages not delivered to the end user because one (or more) error
     * occurred.
     *
     * @param bool $empty @optional Empty message container.
     * @return array Array of messages not delivered because one or more errors
     *         occurred.
     */
    public function getErrors($empty = true)
    {
        sem_acquire($this->sem);
        $messages = $this->getSHMQueue(self::SHM_ERROR_MESSAGES_QUEUE_KEY);
        if ($empty) {
            $this->setSHMQueue(self::SHM_ERROR_MESSAGES_QUEUE_KEY, 0, []);
        }
        sem_release($this->sem);
        return $messages;
    }

    /**
     * The process main loop.
     *
     * During the main loop: the per-process error queue is read and the common error message
     * container is populated; the per-process message queue is spooled (message from
     * this queue is added to ApnsPHPPush queue and delivered).
     */
    protected function mainLoop()
    {
        while (true) {
            pcntl_signal_dispatch();

            if (posix_getppid() != $this->parentPid) {
                $this->logger()->info("Parent process {$this->parentPid} died unexpectedly, exiting...");
                break;
            }

            sem_acquire($this->sem);
            $this->setSHMQueue(
                self::SHM_ERROR_MESSAGES_QUEUE_KEY,
                0,
                array_merge($this->getSHMQueue(self::SHM_ERROR_MESSAGES_QUEUE_KEY), parent::getErrors())
            );

            $queue = $this->getSHMQueue(self::SHM_MESSAGES_QUEUE_KEY_START, $this->currentProcess);
            foreach ($queue as $message) {
                parent::add($message);
            }
            $this->setSHMQueue(self::SHM_MESSAGES_QUEUE_KEY_START, $this->currentProcess);
            sem_release($this->sem);

            $messageAmount = count($queue);
            if ($messageAmount > 0) {
                $this->logger()->info('Process ' . ($this->currentProcess + 1) .
                                       " has {$messageAmount} messages, sending...");
                parent::send();
            } else {
                usleep(self::MAIN_LOOP_USLEEP);
            }
        }
    }

    /**
     * Returns the queue from the shared memory.
     *
     * @param int $queueKey The key of the queue stored in the shared
     *         memory.
     * @param int $process @optional The process cardinal number.
     * @return array Array of messages from the queue.
     */
    protected function getSHMQueue($queueKey, $process = 0)
    {
        if (!shm_has_var($this->shm, $queueKey + $process)) {
            return [];
        }
        return shm_get_var($this->shm, $queueKey + $process);
    }

    /**
     * Store the queue into the shared memory.
     *
     * @param int $queueKey The key of the queue to store in the shared
     *         memory.
     * @param int $process @optional The process cardinal number.
     * @param array $queue @optional The queue to store into shared memory.
     *         The default value is an empty array, useful to empty the queue.
     * @return bool True on success, false otherwise.
     */
    protected function setSHMQueue($queueKey, $process = 0, $queue = [])
    {
        if (!is_array($queue)) {
            $queue = [];
        }
        return shm_put_var($this->shm, $queueKey + $process, $queue);
    }
}
