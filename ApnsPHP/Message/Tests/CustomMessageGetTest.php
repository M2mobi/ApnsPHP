<?php

/**
 * This file contains the CustomMessageGetTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Message\Tests;

/**
 * This class contains tests for the getter functions
 *
 * @covers \ApnsPHP\Message\CustomMessage
 */
class CustomMessageGetTest extends CustomMessageTestCase
{
    /**
     * Test that getActionLocKey() gets the view button title.
     *
     * @covers \ApnsPHP\Message\CustomMessage::getActionLocKey
     */
    public function testGetActionLocKey(): void
    {
        $this->set_reflection_property_value('actionLocKey', 'My Action');

        $value = $this->class->getActionLocKey();

        $this->assertSame('My Action', $value);
    }

    /**
     * Test that getLocKey() gets the alert message string.
     *
     * @covers \ApnsPHP\Message\CustomMessage::getLocKey
     */
    public function testGetLocKey(): void
    {
        $this->set_reflection_property_value('locKey', 'My Alert');

        $value = $this->class->getLocKey();

        $this->assertSame('My Alert', $value);
    }

    /**
     * Test that getLocArgs() gets the format strings.
     *
     * @covers \ApnsPHP\Message\CustomMessage::getLocArgs
     */
    public function testGetLocArgs(): void
    {
        $this->set_reflection_property_value('locArgs', [ 'args' ]);

        $value = $this->class->getLocArgs();

        $this->assertSame([ 'args' ], $value);
    }

    /**
     * Test that getLaunchImage() gets the file name of the launch image.
     *
     * @covers \ApnsPHP\Message\CustomMessage::getLaunchImage
     */
    public function testGetLaunchImage(): void
    {
        $this->set_reflection_property_value('launchImage', 'my-image');

        $value = $this->class->getLaunchImage();

        $this->assertSame('my-image', $value);
    }

    /**
     * Test that getSubTitle() gets the secondary description.
     *
     * @covers \ApnsPHP\Message\CustomMessage::getSubTitle
     */
    public function testGetSubTitle(): void
    {
        $this->set_reflection_property_value('subTitle', 'My amazing notification');

        $value = $this->class->getSubTitle();

        $this->assertSame('My amazing notification', $value);
    }
}
