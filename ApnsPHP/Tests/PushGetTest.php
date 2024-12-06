<?php

/**
 * This file contains the PushGetTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Tests;

/**
 * This class contains tests for the getter functions
 *
 * @covers \ApnsPHP\Push
 */
class PushGetTest extends PushTestCase
{
    /**
     * Test that getSendRetryTimes() returns how often sends should be retried.
     *
     * @covers \ApnsPHP\Push::getSendRetryTimes
     */
    public function testGetSendRetryTimes(): void
    {
        $value = $this->class->getSendRetryTimes();

        $this->assertSame(3, $value);
    }

    /**
     * Test that getWriteInterval() returns the write interval.
     *
     * @covers \ApnsPHP\Push::getWriteInterval
     */
    public function testGetWriteInterval(): void
    {
        $value = $this->class->getWriteInterval();

        $this->assertSame(10000, $value);
    }

    /**
     * Test that getConnectTimeout() returns the connection timeout.
     *
     * @covers \ApnsPHP\Push::getConnectTimeout
     */
    public function testGetConnectTimeout(): void
    {
        $value = $this->class->getConnectTimeout();

        $this->assertSame(10, $value);
    }

    /**
     * Test that getConnectRetryTimes() returns how often connections should be retried.
     *
     * @covers \ApnsPHP\Push::getConnectRetryTimes
     */
    public function testGetConnectRetryTimes(): void
    {
        $value = $this->class->getConnectRetryTimes();

        $this->assertSame(3, $value);
    }

    /**
     * Test that getConnectRetryInterval() returns the connection retry interval.
     *
     * @covers \ApnsPHP\Push::getConnectRetryInterval
     */
    public function testGetConnectRetryInterval(): void
    {
        $value = $this->class->getConnectRetryInterval();

        $this->assertSame(1000000, $value);
    }

    /**
     * Test that getMessageQueue() returns the message queue and leaves it untouched.
     *
     * @covers \ApnsPHP\Push::getMessageQueue
     */
    public function testGetMessageQueue(): void
    {
        $this->set_reflection_property_value('messageQueue', [ 'queue' ]);

        $value = $this->class->getMessageQueue(false);

        $this->assertSame([ 'queue' ], $value);

        $this->assertPropertySame('messageQueue', [ 'queue' ]);
    }

    /**
     * Test that getMessageQueue() returns the message queue and empties it.
     *
     * @covers \ApnsPHP\Push::getMessageQueue
     */
    public function testGetMessageQueueEmptiesQueue(): void
    {
        $this->set_reflection_property_value('messageQueue', [ 'queue' ]);

        $value = $this->class->getMessageQueue();

        $this->assertSame([ 'queue' ], $value);

        $this->assertPropertySame('messageQueue', []);
    }

    /**
     * Test that getErrors() returns the errors and leaves them untouched.
     *
     * @covers \ApnsPHP\Push::getErrors
     */
    public function testGetErrors(): void
    {
        $this->set_reflection_property_value('errors', [ 'errors' ]);

        $value = $this->class->getErrors(false);

        $this->assertSame([ 'errors' ], $value);

        $this->assertPropertySame('errors', [ 'errors' ]);
    }

    /**
     * Test that getErrors() returns the errors and empties them.
     *
     * @covers \ApnsPHP\Push::getErrors
     */
    public function testGetErrorsEmptiesErrors(): void
    {
        $this->set_reflection_property_value('errors', [ 'errors' ]);

        $value = $this->class->getErrors();

        $this->assertSame([ 'errors' ], $value);

        $this->assertPropertySame('errors', []);
    }
}
