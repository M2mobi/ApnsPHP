<?php

/**
 * This file contains the MessageGetTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2023 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Tests;

use ApnsPHP\Message\Priority;
use ApnsPHP\Message\PushType;

/**
 * This class contains tests for the getter functions
 *
 * @covers \ApnsPHP\Message
 */
class MessageGetTest extends MessageTestCase
{
    /**
     * Test that getText() gets the message text.
     *
     * @covers \ApnsPHP\Message::getText
     */
    public function testGetText(): void
    {
        $this->set_reflection_property_value('text', 'My Message');

        $value = $this->class->getText();

        $this->assertSame('My Message', $value);
    }

    /**
     * Test that getTitle() gets the message title.
     *
     * @covers \ApnsPHP\Message::getTitle
     */
    public function testGetTitle(): void
    {
        $this->set_reflection_property_value('title', 'My Title');

        $value = $this->class->getTitle();

        $this->assertSame('My Title', $value);
    }

    /**
     * Test that getBadge() gets the number to badge the application icon with.
     *
     * @covers \ApnsPHP\Message::getBadge
     */
    public function testGetBadge(): void
    {
        $this->set_reflection_property_value('badge', 2);

        $value = $this->class->getBadge();

        $this->assertSame(2, $value);
    }

    /**
     * Test that getSound() gets the sound to play when the message is received.
     *
     * @covers \ApnsPHP\Message::getSound
     */
    public function testGetSound(): void
    {
        $this->set_reflection_property_value('sound', 'jingle');

        $value = $this->class->getSound();

        $this->assertSame('jingle', $value);
    }

    /**
     * Test that getCategory() gets the category of the notification.
     *
     * @covers \ApnsPHP\Message::getCategory
     */
    public function testGetCategory(): void
    {
        $this->set_reflection_property_value('category', 'news-1');

        $value = $this->class->getCategory();

        $this->assertSame('news-1', $value);
    }

    /**
     * Test that getThreadId() gets the thread ID of the notification.
     *
     * @covers \ApnsPHP\Message::getThreadId
     */
    public function testGetThreadId(): void
    {
        $this->set_reflection_property_value('threadId', 'news-1');

        $value = $this->class->getThreadId();

        $this->assertSame('news-1', $value);
    }

    /**
     * Test that getContentAvailable() gets whether to initiate the newsstand background download.
     *
     * @covers \ApnsPHP\Message::getContentAvailable
     */
    public function testGetContentAvailable(): void
    {
        $this->set_reflection_property_value('contentAvailable', true);

        $value = $this->class->getContentAvailable();

        $this->assertTrue($value);
    }

    /**
     * Test that getMutableContent() gets the mutable-content key.
     *
     * @covers \ApnsPHP\Message::getMutableContent
     */
    public function testGetMutableContent(): void
    {
        $this->set_reflection_property_value('mutableContent', true);

        $value = $this->class->getMutableContent();

        $this->assertTrue($value);
    }

    /**
     * Test that getAutoAdjustLongPayload() gets whether to try to auto-adjust a too long payload.
     *
     * @covers \ApnsPHP\Message::getAutoAdjustLongPayload
     */
    public function testGetAutoAdjustLongPayload(): void
    {
        $this->set_reflection_property_value('autoAdjustLongPayload', false);

        $value = $this->class->getAutoAdjustLongPayload();

        $this->assertFalse($value);
    }

    /**
     * Test that getExpiry() gets the time when the message should expire if not already successfully delivered.
     *
     * @covers \ApnsPHP\Message::getExpiry
     */
    public function testGetExpiry(): void
    {
        $this->set_reflection_property_value('expiryValue', 600);

        $value = $this->class->getExpiry();

        $this->assertSame(600, $value);
    }

    /**
     * Test that getTopic() gets the topic of the notification.
     *
     * @covers \ApnsPHP\Message::getTopic
     */
    public function testGetTopic(): void
    {
        $this->set_reflection_property_value('topic', 'My App');

        $value = $this->class->getTopic();

        $this->assertSame('My App', $value);
    }

    /**
     * Test that getCollapseId() gets the collapse ID of the notification.
     *
     * @covers \ApnsPHP\Message::getCollapseId
     */
    public function testGetCollapseId(): void
    {
        $this->set_reflection_property_value('collapseId', 'news-1');

        $value = $this->class->getCollapseId();

        $this->assertSame('news-1', $value);
    }

    /**
     * Test that getPriority() gets the message priority.
     *
     * @covers \ApnsPHP\Message::getPriority
     */
    public function testGetPriority(): void
    {
        $this->set_reflection_property_value('priority', Priority::ConsiderPowerUsage);

        $value = $this->class->getPriority();

        $this->assertSame(Priority::ConsiderPowerUsage, $value);
    }

    /**
     * Test that getPushType() gets the push type.
     *
     * @covers \ApnsPHP\Message::getPushType
     */
    public function testGetPushType(): void
    {
        $this->set_reflection_property_value('pushType', PushType::Alert);

        $value = $this->class->getPushType();

        $this->assertSame(PushType::Alert, $value);
    }

    /**
     * Test that getCustomIdentifier() gets the custom identifier.
     *
     * @covers \ApnsPHP\Message::getCustomIdentifier
     */
    public function testGetCustomIdentifier(): void
    {
        $this->set_reflection_property_value('customIdentifier', '3491ac4b-0681-4c92-8308-d8d8441f4e64');

        $value = $this->class->getCustomIdentifier();

        $this->assertSame('3491ac4b-0681-4c92-8308-d8d8441f4e64', $value);
    }

    /**
     * Test that getCustomPropertyName() gets the first property name.
     *
     * @covers \ApnsPHP\Message::getCustomPropertyName
     */
    public function testGetCustomPropertyNameReturnsFirstPropertyName(): void
    {
        $properties = [
            'my-title'   => 'My Title',
            'my-message' => 'My Message',
        ];

        $this->set_reflection_property_value('customProperties', $properties);

        $value = $this->class->getCustomPropertyName();

        $this->assertSame('my-title', $value);
    }

    /**
     * Test that getCustomPropertyName() throws an exception if no custom property is set.
     *
     * @covers \ApnsPHP\Message::getCustomPropertyName
     */
    public function testGetCustomPropertyNameThrowsExceptionWhenNoneSet(): void
    {
        $this->expectException('ApnsPHP\Message\Exception');
        $this->expectExceptionMessage('No custom property exists!');

        $this->class->getCustomPropertyName();
    }

    /**
     * Test that getCustomPropertyValue() gets the first property value.
     *
     * @covers \ApnsPHP\Message::getCustomPropertyValue
     */
    public function testGetCustomPropertyValueReturnsFirstPropertyValue(): void
    {
        $properties = [
            'my-title'   => 'My Title',
            'my-message' => 'My Message',
        ];

        $this->set_reflection_property_value('customProperties', $properties);

        $value = $this->class->getCustomPropertyValue();

        $this->assertSame('My Title', $value);
    }

    /**
     * Test that getCustomPropertyValue() throws an exception if no custom property is set.
     *
     * @covers \ApnsPHP\Message::getCustomPropertyValue
     */
    public function testGetCustomPropertyValueThrowsExceptionWhenNoneSet(): void
    {
        $this->expectException('ApnsPHP\Message\Exception');
        $this->expectExceptionMessage('No custom property exists!');

        $this->class->getCustomPropertyValue();
    }

    /**
     * Test that getCustomPropertyValue() successfully returns an existing property.
     *
     * @covers \ApnsPHP\Message::getCustomProperty
     */
    public function testGetCustomPropertyWithExistingPropertyName(): void
    {
        $properties = [
            'my-title'   => 'My Title',
            'my-message' => 'My Message',
        ];

        $this->set_reflection_property_value('customProperties', $properties);

        $value = $this->class->getCustomProperty('my-title');

        $this->assertSame('My Title', $value);
    }

    /**
     * Test that getCustomProperty() throws an exception if the custom property is not set.
     *
     * @covers \ApnsPHP\Message::getCustomProperty
     */
    public function testGetCustomPropertyThrowsExceptionWhenNotExists(): void
    {
        $properties = [
            'my-title'   => 'My Title',
            'my-message' => 'My Message',
        ];

        $this->set_reflection_property_value('customProperties', $properties);

        $this->expectException('ApnsPHP\Message\Exception');
        $this->expectExceptionMessage("No property exists with the specified name 'my-body'.");

        $this->class->getCustomProperty('my-body');
    }

    /**
     * Test that getCustomPropertyNames() successfully returns the names of the set properties.
     *
     * @covers \ApnsPHP\Message::getCustomPropertyNames
     */
    public function testGetCustomPropertyNamesWithPropertiesSet(): void
    {
        $properties = [
            'my-title'   => 'My Title',
            'my-message' => 'My Message',
        ];

        $this->set_reflection_property_value('customProperties', $properties);

        $value = $this->class->getCustomPropertyNames();

        $this->assertSame([ 'my-title', 'my-message' ], $value);
    }

    /**
     * Test that getCustomPropertyNames() returns an empty array if no properties set.
     *
     * @covers \ApnsPHP\Message::getCustomPropertyNames
     */
    public function testGetCustomPropertyNamesWithoutPropertiesSet(): void
    {
        $value = $this->class->getCustomPropertyNames();

        $this->assertArrayEmpty($value);
    }

    /**
     * Test that getRecipientsNumber() returns the count of set device tokens.
     *
     * @covers \ApnsPHP\Message::getRecipientsNumber
     */
    public function testGetRecipientsNumber(): void
    {
        $tokens = [
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485L',
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485B'
        ];

        $this->set_reflection_property_value('deviceTokens', $tokens);

        $value = $this->class->getRecipientsNumber();

        $this->assertSame(2, $value);
    }

    /**
     * Test that getRecipientsCount() returns the count of set device tokens.
     *
     * @covers \ApnsPHP\Message::getRecipientsCount
     */
    public function testGetRecipientsCount(): void
    {
        $tokens = [
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485L',
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485B'
        ];

        $this->set_reflection_property_value('deviceTokens', $tokens);

        $value = $this->class->getRecipientsCount();

        $this->assertSame(2, $value);
    }

    /**
     * Test that getRecipients() returns the set device tokens.
     *
     * @covers \ApnsPHP\Message::getRecipients
     */
    public function testGetRecipients(): void
    {
        $tokens = [
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485L',
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485B'
        ];

        $this->set_reflection_property_value('deviceTokens', $tokens);

        $value = $this->class->getRecipients();

        $this->assertSame($tokens, $value);
    }

    /**
     * Test that getRecipient() returns an existing device token.
     *
     * @covers \ApnsPHP\Message::getRecipient
     */
    public function testGetExistingRecipient(): void
    {
        $tokens = [
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485L',
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485B'
        ];

        $this->set_reflection_property_value('deviceTokens', $tokens);

        $value = $this->class->getRecipient(1);

        $this->assertSame('1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485B', $value);
    }

    /**
     * Test that getRecipient() throws an exception when trying to return a device token that is out of bounds.
     *
     * @covers \ApnsPHP\Message::getRecipient
     */
    public function testGetOutOfBoundsRecipient(): void
    {
        $tokens = [
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485L',
            '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485B'
        ];

        $this->set_reflection_property_value('deviceTokens', $tokens);

        $this->expectException('ApnsPHP\Message\Exception');
        $this->expectExceptionMessage("No recipient at index '3'");

        $this->class->getRecipient(3);
    }
}
