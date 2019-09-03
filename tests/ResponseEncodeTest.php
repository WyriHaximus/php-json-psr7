<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use RingCentral\Psr7\Response;
use WyriHaximus;

/**
 * @internal
 */
final class ResponseEncodeTest extends TestCase
{
    /**
     * @dataProvider \WyriHaximus\Tests\Provider::response
     */
    public function testSuccess(ResponseInterface $response): void
    {
        $json = WyriHaximus\psr7_response_encode($response);
        self::assertSame(
            [
                'protocol_version' => '2.0',
                'status_code' => 200,
                'reason_phrase' => 'awesome',
                'headers' => [
                    'foo' => [
                        'bar',
                    ],
                ],
                'body' => 'YmVlcg==',
            ],
            $json
        );

        self::assertSame('beer', (string)$response->getBody());
    }
}
