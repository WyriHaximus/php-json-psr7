<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use WyriHaximus;

use function Safe\json_encode;

/**
 * @internal
 */
final class ResponseJsonEncodeTest extends TestCase
{
    /**
     * @dataProvider \WyriHaximus\Tests\Provider::response
     */
    public function testSuccess(ResponseInterface $response): void
    {
        $json = WyriHaximus\psr7_response_json_encode($response);
        self::assertSame(
            json_encode([
                'protocol_version' => '2',
                'status_code' => 200,
                'reason_phrase' => 'OK',
                'headers' => [
                    'foo' => ['bar'],
                ],
                'body' => 'YmVlcg==',
            ]),
            $json
        );

        self::assertSame('beer', (string) $response->getBody());
    }
}
