<?php

/**
 * This file contains the SafariMessageTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Message\Tests;

use ApnsPHP\Message\SafariMessage;
use Lunr\Halo\LunrBaseTest;
use ReflectionClass;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the SafariMessage class.
 *
 * @covers \ApnsPHP\Message\SafariMessage
 */
abstract class SafariMessageTestCase extends LunrBaseTest
{
    /**
     * Class to test
     * @var SafariMessage
     */
    protected SafariMessage $class;

    /**
     * TestCase constructor
     */
    public function setUp(): void
    {
        $this->class      = new SafariMessage();
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
