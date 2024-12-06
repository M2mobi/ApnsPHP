<?php

/**
 * This file contains the PushInvalidTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Tests;

use ApnsPHP\Environment;
use ApnsPHP\Push;

/**
 * This class contains tests for exceptions in case of invalid constructor args
 *
 * @covers \ApnsPHP\Push
 */
class PushInvalidTest extends PushTestCase
{
    /**
     * TestCase constructor
     */
    public function setUp(): void
    {
        $this->logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();
    }

    /**
     * Test that trying to instantiate the Push class with an non-existing provider certificate file
     * throws an exception.
     *
     * @covers \ApnsPHP\Push::__construct
     */
    public function testConstructWithNonExistingProviderCertificateFile(): void
    {
        $this->expectException('ApnsPHP\Push\Exception');
        $this->expectExceptionMessage("Unable to read certificate file 'server_certificates_bundle_invalid.pem'");

        $this->class = new Push(Environment::Production, 'server_certificates_bundle_invalid.pem', $this->logger);
    }

    /**
     * Test that trying to instantiate the Push class with an unreadable provider certificate file throws an exception.
     *
     * @covers \ApnsPHP\Push::__construct
     */
    public function testConstructWithUnreadableProviderCertificateFile(): void
    {
        $this->expectException('ApnsPHP\Push\Exception');
        $this->expectExceptionMessage("Unable to read certificate file 'server_certificates_bundle_unreadable.pem'");

        $this->class = new Push(Environment::Production, 'server_certificates_bundle_unreadable.pem', $this->logger);
    }
}
