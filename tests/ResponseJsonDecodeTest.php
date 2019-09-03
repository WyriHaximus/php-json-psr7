<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use WyriHaximus;

/**
 * @internal
 */
final class ResponseJsonDecodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $json = \json_encode([
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
        self::assertSame('beer', (string)$response->getBody());
        self::assertSame('beer', (string)$response->getBody());
    }

    public function testFailure(): void
    {
        self::expectException(WyriHaximus\NotAnEncodedResponseException::class);
        self::expectExceptionMessage('"[]" is not an encoded PSR-7 response, field "protocol_version" is missing');

        WyriHaximus\psr7_response_json_decode('[]');
    }
}
