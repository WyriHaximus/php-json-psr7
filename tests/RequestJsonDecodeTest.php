<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus;

use function Safe\json_encode;

/**
 * @internal
 */
final class RequestJsonDecodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $json = json_encode([
            'protocol_version' => '2',
            'method' => 'GET',
            'uri' => 'https://www.example.com/',
            'headers' => [
                'Host' => ['www.example.com'],
                'foo' => ['bar'],
            ],
            'body' => 'YmVlcg==',
        ]);

        $request = WyriHaximus\psr7_request_json_decode($json);
        self::assertSame('2', $request->getProtocolVersion());
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.example.com/', (string) $request->getUri());
        self::assertSame([
            'Host' => ['www.example.com'],
            'foo' => ['bar'],
        ], $request->getHeaders());
        self::assertSame('beer', (string) $request->getBody());
        self::assertSame('beer', (string) $request->getBody());
    }

    public function testFailure(): void
    {
        self::expectException(WyriHaximus\NotAnEncodedRequestException::class);
        self::expectExceptionMessage('"[]" is not an encoded PSR-7 request, field "protocol_version" is missing');

        WyriHaximus\psr7_request_json_decode('[]');
    }
}
