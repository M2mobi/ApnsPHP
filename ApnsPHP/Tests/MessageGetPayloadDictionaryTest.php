<?php

/**
 * This file contains the MessageGetPayloadDictionaryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Tests;

/**
 * This class contains tests for the getPayloadDictionary function
 *
 * @covers \ApnsPHP\Message
 */
class MessageGetPayloadDictionaryTest extends MessageTestCase
{
    /**
     * Test that getPayloadDictionary returns complete payload
     *
     * @covers \ApnsPHP\Message::getPayloadDictionary
     */
    public function testGetPayloadDictionaryReturnsCompletePayload(): void
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

        $payload = [
            'aps' => [
                'alert' => [
                    'title' => 'Were no strangers to love',
                    'body'  => 'You know the rules, and so do I'
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
     * Test that getPayloadDictionary returns payload without title
     *
     * @covers \ApnsPHP\Message::getPayloadDictionary
     */
    public function testGetPayloadDictionaryWithoutTitle(): void
    {
        $this->class->setText('Never gonna give you up');
        $this->class->setBadge(1);
        $this->class->setSound('default');
        $this->class->setContentAvailable(true);
        $this->class->setMutableContent(true);
        $this->class->setCategory('something');
        $this->class->setThreadId('thisIsAThreadId');
        $this->class->setCustomProperty('property', 'property');
        $this->class->setCustomProperty('name', 'value');

        $payload = [
            'aps' => [
                'alert'             => 'Never gonna give you up',
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
     * @covers \ApnsPHP\Message::getPayloadDictionary
     */
    public function testGetPayloadDictionaryReturnsEmptyPayload(): void
    {
        $payload = [ 'aps' => [] ];

        $result = $this->get_reflection_method('getPayloadDictionary')->invoke($this->class);

        $this->assertEquals($payload, $result);
    }
}
