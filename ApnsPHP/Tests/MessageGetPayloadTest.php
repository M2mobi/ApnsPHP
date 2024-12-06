<?php

/**
 * This file contains the MessageGetPayloadTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Tests;

/**
 * This class contains tests for the getPayload function
 *
 * @covers \ApnsPHP\Message
 */
class MessageGetPayloadTest extends MessageTestCase
{
    /**
     * Test that getPayload() returns complete JSON encoded payload
     *
     * @covers \ApnsPHP\Message::getPayload
     */
    public function testGetPayloadReturnsCompletePayload(): void
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

        $payload = '{"aps":{"alert":{"title":"Were no strangers to love","body":"You know the rules, and so do I"},' .
                   '"badge":1,"sound":"default","content-available":1,"mutable-content":1,"category":"something",' .
                   '"thread-id":"thisIsAThreadId"},"property":"property","name":"value"}';

        $result = $this->class->getPayload();

        $this->assertEquals($payload, $result);
    }

    /**
     * Test that getPayload() returns empty JSON encoded payload when nothing is set
     *
     * @covers \ApnsPHP\Message::getPayload
     */
    public function testGetPayloadReturnsEmptyPayload(): void
    {
        $result = $this->class->getPayload();

        $this->assertEquals('{"aps":{}}', $result);
    }

    /**
     * Test that getPayload() throws an exception when the payload is too long and autoAdjustLongPayload is false.
     *
     * @covers \ApnsPHP\Message::getPayload
     */
    public function testGetPayloadThrowsExceptionOnTooLongPayload(): void
    {
        $this->class->setText($this->getLargeString(4077));
        $this->class->setAutoAdjustLongPayload(false);

        $this->expectException('ApnsPHP\Exception');
        $this->expectExceptionMessage('JSON Payload is too long: 4097 bytes. Maximum size is 4096 bytes');

        $this->class->getPayload();
    }

    /**
     * Test that getPayload() throws an exception when the payload is too long and can't be auto adjusted.
     *
     * @covers \ApnsPHP\Message::getPayload
     */
    public function testGetPayloadThrowsExceptionOnTooLongPayloadWithoutAutoAdjust(): void
    {
        $this->class->setTitle($this->getLargeString(4056));
        $this->class->setText($this->getLargeString(20));

        $this->expectException('ApnsPHP\Exception');
        $this->expectExceptionMessage('JSON Payload is too long: 4116 bytes. Maximum size is 4096 bytes.' .
                                      ' The message text can not be auto-adjusted.');

        $this->class->getPayload();
    }

    /**
     * Test that getPayload() throws an exception when the payload is too long and can't be auto adjusted.
     *
     * @covers \ApnsPHP\Message::getPayload
     */
    public function testGetPayloadWillAutoAdjustTooLongMessage(): void
    {
        $full_body = $this->getLargeString(4056);
        $title     = $this->getLargeString(20);

        $this->class->setTitle($title);
        $this->class->setText($full_body);

        $body = substr($full_body, 0, 4035);

        $payload = '{"aps":{"alert":{"title":"' . $title . '","body":"' . $body . '"}}}';

        $result = $this->class->getPayload();

        $this->assertEquals($payload, $result);
    }

    /**
     * Test that the class returns the JSON encoded payload when cast to string
     *
     * @covers \ApnsPHP\Message::__toString
     */
    public function testCastToStringReturnsCompletePayload(): void
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

        $payload = '{"aps":{"alert":{"title":"Were no strangers to love","body":"You know the rules, and so do I"},' .
                   '"badge":1,"sound":"default","content-available":1,"mutable-content":1,"category":"something",' .
                   '"thread-id":"thisIsAThreadId"},"property":"property","name":"value"}';

        $result = (string) $this->class;

        $this->assertEquals($payload, $result);
    }

    /**
     * Test that the class returns an empty string when cast to string and the payload is too long
     * and can't be auto adjusted.
     *
     * @covers \ApnsPHP\Message::__toString
     */
    public function testCastToStringReturnsEmptyStringOnTooLongPayloadWithoutAutoAdjust(): void
    {
        $this->class->setTitle($this->getLargeString(4056));
        $this->class->setText($this->getLargeString(20));

        $result = (string) $this->class;

        $this->assertSame('', $result);
    }

    /**
     * Return a string that is a certain size in bytes
     *
     * @param int $size String size
     *
     * @return string String of certain size in bytes
     */
    private function getLargeString(int $size): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length     = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $size; $i++) {
            $randomString .= $characters[rand(0, $length - 1)];
        }
        return $randomString;
    }
}
