<?php

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use RingCentral\Psr7\Request;
use WyriHaximus;

final class RequestJsonEncodeTest extends TestCase
{
    public function testSuccess()
    {
        $request = new Request(
            'GET',
            'https://www.example.com/',
            [
                'foo' => 'bar',
            ],
            'beer',
            '2.0'
        );

        $json = WyriHaximus\psr7_request_json_encode($request);
        self::assertSame(
            json_encode([
                'protocol_version' => '2.0',
                'method' => 'GET',
                'uri' => 'https://www.example.com/',
                'headers' => [
                    'Host' => [
                        'www.example.com',
                    ],
                    'foo' => [
                        'bar',
                    ],
                ],
                'body' => 'YmVlcg==',
            ]),
            $json
        );
    }
}
