<?php

/**
 * This file contains the MessageTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Tests;

use ApnsPHP\Message;
use Lunr\Halo\LunrBaseTest;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the Message class.
 *
 * @covers \ApnsPHP\Message
 */
abstract class MessageTestCase extends LunrBaseTest
{
    /**
     * Class to test
     * @var Message
     */
    protected Message $class;

    /**
     * TestCase constructor
     */
    public function setUp(): void
    {
        $this->class = new Message();
        $this->baseSetUp($this->class);
    }

    /**
     * TestCase destructor
     */
    public function tearDown(): void
    {
        unset($this->class);
        parent::tearDown();
    }
}
