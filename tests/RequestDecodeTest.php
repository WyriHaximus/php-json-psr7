<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus;

/**
 * @internal
 */
final class RequestDecodeTest extends TestCase
{
    public function testSuccess(): void
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

    /**
     * @expectedException WyriHaximus\NotAnEncodedRequestException
     * @expectedExceptionMessage "[]" is not an encoded PSR-7 request, field "protocol_version" is missing
     */
    public function testFailure(): void
    {
        WyriHaximus\psr7_request_decode([]);
    }
}
