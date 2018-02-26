<?php

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus;

final class ResponseJsonDecodeTest extends TestCase
{
    public function test()
    {
        $json = json_encode([
            'protocol_version' => '2.0',
            'status_code' => 200,
            'reason_phrase' => 'awesome',
            'headers' => [
                'foo' => [
                    'bar',
                ],
            ],
            'body' => 'YmVlcg==',
        ]);

        $response = WyriHaximus\psr7_response_json_decode($json);
        self::assertSame('2.0', $response->getProtocolVersion());
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('awesome', $response->getReasonPhrase());
        self::assertSame([
            'foo' => [
                'bar',
            ],
        ], $response->getHeaders());
        self::assertSame('beer', $response->getBody()->getContents());
    }
}
