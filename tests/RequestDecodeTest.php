<?php

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus;

final class RequestDecodeTest extends TestCase
{
    public function test()
    {
        $json = [
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
        ];

        $request = WyriHaximus\psr7_request_decode($json);
        self::assertSame('2.0', $request->getProtocolVersion());
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.example.com/', (string)$request->getUri());
        self::assertSame([
            'Host' => [
                'www.example.com',
            ],
            'foo' => [
                'bar',
            ],
        ], $request->getHeaders());
        self::assertSame('beer', $request->getBody()->getContents());
    }
}
