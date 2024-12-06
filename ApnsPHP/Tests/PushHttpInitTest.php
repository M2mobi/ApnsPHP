<?php

/**
 * This file contains the PushHttpInitTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Tests;

use ApnsPHP\Exception;
use Lcobucci\JWT\Token\DataSet;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Token\Signature;

/**
 * This class contains tests for the httpInit function
 *
 * @covers \ApnsPHP\Push
 */
class PushHttpInitTest extends PushTestCase
{
    /**
     * Test that httpInit() succeeds with certificate
     *
     * @covers \ApnsPHP\Push::httpInit
     */
    public function testHttpInitSucceedsWithCertificate(): void
    {
        $this->set_reflection_property_value('providerCertFile', 'cert.pem');
        $this->set_reflection_property_value('logger', $this->logger);

        $message = [
            ['Trying to initialize HTTP/2 backend...'],
            ['Initializing HTTP/2 backend with certificate.'],
            ['Initialized HTTP/2 backend.']
        ];

        $this->logger->expects($this->exactly(3))
                     ->method('info')
                     ->willReturnMap($message);

        $method = $this->get_reflection_method('httpInit');
        $result = $method->invoke($this->class);

        $this->assertTrue($result);
    }

    /**
     * Test that httpInit() succeeds with key
     *
     * @covers \ApnsPHP\Push::httpInit
     */
    public function testHttpInitSucceedsWithKey(): void
    {
        $this->set_reflection_property_value('providerCertFile', 'key.p8');
        $this->set_reflection_property_value('providerTeamId', 'TheTeam');
        $this->set_reflection_property_value('providerKeyId', 'TheKey');
        $this->set_reflection_property_value('logger', $this->logger);

        $key = $this->getMockBuilder('Lcobucci\JWT\Signer\Key')
                    ->disableOriginalConstructor()
                    ->getMock();

        $builder = $this->getMockBuilder('Lcobucci\JWT\Builder')
                        ->getMock();

        $token = new Plain(
            new DataSet([ 'headers' => 'foo' ], 'eHeaders'),
            new DataSet([ 'claims' => 'bar' ], 'eClaims'),
            new Signature('signature', 'eSignature'),
        );

        $this->mock_method([ 'Lcobucci\JWT\Signer\Key\InMemory', 'file' ], fn() => $key);

        $this->mock_method([ 'Lcobucci\JWT\Configuration', 'builder' ], fn() => $builder);

        $builder->expects($this->once())
                ->method('issuedBy')
                ->with('TheTeam')
                ->willReturnSelf();

        $builder->expects($this->once())
                ->method('issuedAt')
                ->with($this->isInstanceof('DateTimeImmutable'))
                ->willReturnSelf();

        $builder->expects($this->once())
                ->method('withHeader')
                ->with('kid', 'TheKey')
                ->willReturnSelf();

        $builder->expects($this->once())
                ->method('getToken')
                ->with($this->isInstanceOf('Lcobucci\JWT\Signer\Ecdsa\Sha256'), $key)
                ->willReturn($token);

        $this->logger->expects($this->exactly(3))
                     ->method('info')
                     ->willReturnMap([
                         [ 'Trying to initialize HTTP/2 backend...' ],
                         [ 'Initializing HTTP/2 backend with key.' ],
                         [ 'Initialized HTTP/2 backend.' ]
                     ]);

        $method = $this->get_reflection_method('httpInit');
        $result = $method->invoke($this->class);

        $this->assertTrue($result);

        $this->unmock_method([ 'Lcobucci\JWT\Signer\Key\InMemory', 'file' ]);
        $this->unmock_method([ 'Lcobucci\JWT\Configuration', 'builder' ]);
    }

    /**
     * Test that httpInit() throws an exception when curl_setopt_array() fails
     *
     * @covers \ApnsPHP\Push::httpInit
     */
    public function testHttpInitThrowsExceptionOnCurlSetoptFail(): void
    {
        $this->set_reflection_property_value('providerCertFile', 'key.p8');
        $this->set_reflection_property_value('providerTeamId', 'TheTeam');
        $this->set_reflection_property_value('providerKeyId', 'TheKey');
        $this->set_reflection_property_value('logger', $this->logger);

        $this->mock_function('curl_setopt_array', fn() => false);

        $key = $this->getMockBuilder('Lcobucci\JWT\Signer\Key')
                    ->disableOriginalConstructor()
                    ->getMock();

        $builder = $this->getMockBuilder('Lcobucci\JWT\Builder')
                        ->getMock();

        $token = new Plain(
            new DataSet([ 'headers' => 'foo' ], 'eHeaders'),
            new DataSet([ 'claims' => 'bar' ], 'eClaims'),
            new Signature('signature', 'eSignature'),
        );

        $this->mock_method([ 'Lcobucci\JWT\Signer\Key\InMemory', 'file' ], fn() => $key);

        $this->mock_method([ 'Lcobucci\JWT\Configuration', 'builder' ], fn() => $builder);

        $builder->expects($this->once())
                ->method('issuedBy')
                ->with('TheTeam')
                ->willReturnSelf();

        $builder->expects($this->once())
                ->method('issuedAt')
                ->with($this->isInstanceof('DateTimeImmutable'))
                ->willReturnSelf();

        $builder->expects($this->once())
                ->method('withHeader')
                ->with('kid', 'TheKey')
                ->willReturnSelf();

        $builder->expects($this->once())
                ->method('getToken')
                ->with($this->isInstanceOf('Lcobucci\JWT\Signer\Ecdsa\Sha256'), $key)
                ->willReturn($token);

        $this->logger->expects($this->exactly(2))
                     ->method('info')
                     ->willReturnMap([
                         [ 'Trying to initialize HTTP/2 backend...' ],
                         [ 'Initializing HTTP/2 backend with key.' ],
                     ]);

        $this->expectException('ApnsPHP\Exception');
        $this->expectExceptionMessage('Unable to initialize HTTP/2 backend.');

        $method = $this->get_reflection_method('httpInit');

        # phpstan doesn't detect the potential throw through ReflectionMethod.
        # Verified that by making the method public and calling it directly
        # it's detected just fine.
        # May be https://github.com/phpstan/phpstan/issues/7719
        try {
            $method->invoke($this->class);
        } catch (Exception $e) { /* @phpstan-ignore-line */
            $this->unmock_function('curl_setopt_array');
            $this->unmock_method([ 'Lcobucci\JWT\Signer\Key\InMemory', 'file' ]);
            $this->unmock_method([ 'Lcobucci\JWT\Configuration', 'builder' ]);

            throw $e;
        }
    }
}
