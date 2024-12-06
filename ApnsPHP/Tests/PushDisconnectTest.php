<?php

/**
 * This file contains the PushDisconnectTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Tests;

/**
 * This class contains tests for the disconnect function
 *
 * @covers \ApnsPHP\Push
 */
class PushDisconnectTest extends PushTestCase
{
    /**
     * Test that disconnect() disconnects successfully
     *
     * @covers \ApnsPHP\Push::disconnect
     */
    public function testDisconnectSuccess(): void
    {
        $this->set_reflection_property_value('hSocket', curl_init());
        $this->set_reflection_property_value('logger', $this->logger);

        $this->logger->expects($this->once())
                     ->method('info')
                     ->with('Disconnected.');

        $result =  $this->class->disconnect();

        $this->assertTrue($result);
    }

    /**
     * Test that disconnect() disconnects returns false if it isn't connected to begin with
     *
     * @covers \ApnsPHP\Push::disconnect
     */
    public function testDisconnectReturnsFalse(): void
    {
        $result = $this->class->disconnect();

        $this->assertFalse($result);
    }
}
