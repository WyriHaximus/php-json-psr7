<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use RingCentral\Psr7\Request;
use WyriHaximus;

/**
 * @internal
 */
final class RequestJsonEncodeTest extends TestCase
{
    public function testSuccess(): void
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
            \json_encode([
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

        self::assertSame('beer', (string)$request->getBody());
    }
}
