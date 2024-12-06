<?php

/**
 * This file contains the SafariMessageGetPayloadDictionaryTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Message\Tests;

/**
 * This class contains tests for the getPayloadDictionary function
 *
 * @covers \ApnsPHP\Message\SafariMessage
 */
class SafariMessageGetPayloadDictionaryTest extends SafariMessageTestCase
{
    /**
     * Test that getPayloadDictionary returns complete payload
     *
     * @covers \ApnsPHP\Message\SafariMessage::getPayloadDictionary
     */
    public function testGetPayloadDictionaryReturnsCompletePayload(): void
    {
        $this->class->setTitle('Never gonna give you up');
        $this->class->setText('Never gonna let you down');
        $this->class->setAction('label');
        $this->class->setUrlArgs([ 'value', 'value' ]);

        $payload = [
            'aps' => [
                'alert' => [
                    'title'  => 'Never gonna give you up',
                    'body'   => 'Never gonna let you down',
                    'action' => 'label'
                ],
                'url-args' => [ 'value', 'value' ]
            ]
        ];

        $result = $this->get_reflection_method('getPayloadDictionary')->invoke($this->class);

        $this->assertEquals($payload, $result);
    }

    /**
     * Test that getPayloadDictionary returns empty payload if nothing is set
     *
     * @covers \ApnsPHP\Message\SafariMessage::getPayloadDictionary
     */
    public function testGetPayloadDictionaryReturnsEmptyPayload(): void
    {
        $payload = [ 'aps' => [ 'alert' => [] ] ];

        $result = $this->get_reflection_method('getPayloadDictionary')->invoke($this->class);

        $this->assertEquals($payload, $result);
    }
}
