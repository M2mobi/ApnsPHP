<?php

/**
 * This file contains the CustomMessageGetPayloadDictionaryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Message\Tests;

/**
 * This class contains tests for the getPayloadDictionary function
 *
 * @covers \ApnsPHP\Message\CustomMessage
 */
class CustomMessageGetPayloadDictionaryTest extends CustomMessageTestCase
{
    /**
     * Test that getPayloadDictionary returns complete payload with body if locKey isn't set
     *
     * @covers \ApnsPHP\Message\CustomMessage::getPayloadDictionary
     */
    public function testGetPayloadDictionaryReturnsCompletePayloadWithoutLocKey(): void
    {
        $this->class->setTitle('Were no strangers to love');
        $this->class->setText('You know the rules, and so do I');
        $this->class->setBadge(1);
        $this->class->setSound('default');
        $this->class->setContentAvailable(true);
        $this->class->setMutableContent(true);
        $this->class->setCategory('something');
        $this->class->setThreadId('thisIsAThreadId');
        $this->class->setCustomProperty('property', 'property');
        $this->class->setCustomProperty('name', 'value');
        $this->class->setActionLocKey('Button title');
        $this->class->setLocArgs([ 'value', 'value' ]);
        $this->class->setLaunchImage('filename');
        $this->class->setSubTitle('Never gonna give you up');

        $payload = [
            'aps' => [
                'alert' => [
                    'title'          => 'Were no strangers to love',
                    'body'           => 'You know the rules, and so do I',
                    'action-loc-key' => 'Button title',
                    'loc-args'       => [ 'value', 'value' ],
                    'launch-image'   => 'filename',
                    'subtitle'       => 'Never gonna give you up'
                ],
                'badge'             => 1,
                'sound'             => 'default',
                'content-available' => 1,
                'mutable-content'   => 1,
                'category'          => 'something',
                'thread-id'         => 'thisIsAThreadId'
            ],
            'property' => 'property',
            'name' => 'value'
        ];

        $result = $this->get_reflection_method('getPayloadDictionary')->invoke($this->class);

        $this->assertEquals($payload, $result);
    }

    /**
     * Test that getPayloadDictionary returns complete payload without body if locKey is set
     *
     * @covers \ApnsPHP\Message\CustomMessage::getPayloadDictionary
     */
    public function testGetPayloadDictionaryReturnsCompletePayloadWithoutBody(): void
    {
        $this->class->setTitle('Were no strangers to love');
        $this->class->setText('You know the rules, and so do I');
        $this->class->setBadge(1);
        $this->class->setSound('default');
        $this->class->setContentAvailable(true);
        $this->class->setMutableContent(true);
        $this->class->setCategory('something');
        $this->class->setThreadId('thisIsAThreadId');
        $this->class->setCustomProperty('property', 'property');
        $this->class->setCustomProperty('name', 'value');
        $this->class->setActionLocKey('Button title');
        $this->class->setLocKey('localization string');
        $this->class->setLocArgs([ 'value', 'value' ]);
        $this->class->setLaunchImage('filename');
        $this->class->setSubTitle('Never gonna give you up');

        $payload = [
            'aps' => [
                'alert' => [
                    'title'          => 'Were no strangers to love',
                    'action-loc-key' => 'Button title',
                    'loc-key'        => 'localization string',
                    'loc-args'       => [ 'value', 'value' ],
                    'launch-image'   => 'filename',
                    'subtitle'       => 'Never gonna give you up'
                ],
                'badge'             => 1,
                'sound'             => 'default',
                'content-available' => 1,
                'mutable-content'   => 1,
                'category'          => 'something',
                'thread-id'         => 'thisIsAThreadId'
            ],
            'property' => 'property',
            'name' => 'value'
        ];

        $result = $this->get_reflection_method('getPayloadDictionary')->invoke($this->class);

        $this->assertEquals($payload, $result);
    }

    /**
     * Test that getPayloadDictionary returns an empty payload if nothing is set
     *
     * @covers \ApnsPHP\Message\CustomMessage::getPayloadDictionary
     */
    public function testGetPayloadDictionaryReturnsEmptyPayload(): void
    {
        $payload = [ 'aps' => [ 'alert' => [] ] ];

        $result = $this->get_reflection_method('getPayloadDictionary')->invoke($this->class);

        $this->assertEquals($payload, $result);
    }
}
