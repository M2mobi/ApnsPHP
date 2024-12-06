<?php

/**
 * This file contains the SafariMessageGetTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Message\Tests;

/**
 * This class contains tests for the getter functions
 *
 * @covers \ApnsPHP\Message\SafariMessage
 */
class SafariMessageGetTest extends SafariMessageTestCase
{
    /**
     * Test that getAction() gets the label of the action button.
     *
     * @covers \ApnsPHP\Message\SafariMessage::getAction
     */
    public function testGetAction(): void
    {
        $this->set_reflection_property_value('action', 'My Action');

        $value = $this->class->getAction();

        $this->assertSame('My Action', $value);
    }

    /**
     * Test that getUrlArgs() gets the URL args.
     *
     * @covers \ApnsPHP\Message\SafariMessage::getUrlArgs
     */
    public function testGetUrlArgs(): void
    {
        $this->set_reflection_property_value('urlArgs', [ 'args' ]);

        $value = $this->class->getUrlArgs();

        $this->assertSame([ 'args' ], $value);
    }
}
