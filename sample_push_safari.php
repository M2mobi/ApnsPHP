<?php

/**
 * SafariMessage push demo.
 *
 * phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols, PSR1.Classes.ClassDeclaration.MissingNamespace
 *
 * SPDX-FileCopyrightText: Copyright 2017 Marco Rocca (marco.rocca@delitestudio.com)
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-3-Clause
 */

// Adjust to your timezone
date_default_timezone_set('Europe/Rome');

// Report all PHP errors
error_reporting(-1);

// Using Autoload all classes are loaded on-demand
require_once 'vendor/autoload.php';

class SampleLogger extends \Psr\Log\AbstractLogger
{
    public function log($level, $message, array $context = []): void
    {
        printf("%s: %s ApnsPHP[%d]: %s\n", date('r'), strtoupper($level), getmypid(), trim($message));
    }
}

// Instantiate a new ApnsPHP_Push object
$push = new \ApnsPHP\Push(
    \ApnsPHP\Push::ENVIRONMENT_SANDBOX,
    'server_certificates_bundle_sandbox.pem',
    new SampleLogger(),
);

// Set the Provider Certificate passphrase
// $push->setProviderCertificatePassphrase('test');

// Connect to the Apple Push Notification Service
$push->connect();

// Instantiate a new SafariMessage message with a single recipient
$message = new \ApnsPHP\Message\SafariMessage('1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485');

// Set the title of the notification.
$message->setTitle('Flight A998 Now Boarding');

// Set the body of the notification.
$message->setText('Boarding has begun for Flight A998.');

// Set the label of the action button, if the user sets the notifications to appear as alerts.
// This label should be succinct, such as "Details" or "Read more". If omitted, the default value is "Show".
$message->setAction('View');

// Set an array of values that are paired with the placeholders inside the urlFormatString value
// of your website.json file
$message->setUrlArgs(['boarding', 'A998']);

// Add the message to the message queue
$push->add($message);

// Send all messages in the message queue
$push->send();

// Disconnect from the Apple Push Notification Service
$push->disconnect();

// Examine the error message container
$aErrorQueue = $push->getErrors();
if (!empty($aErrorQueue)) {
    var_dump($aErrorQueue);
}
