<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use WyriHaximus;

use function Safe\json_encode;

final class RequestJsonEncodeTest extends TestCase
{
    /**
     * @test
     * @dataProvider \WyriHaximus\Tests\Provider::request
     */
    public function success(RequestInterface $request): void
    {
        $json = WyriHaximus\psr7_request_json_encode($request);
        self::assertSame(
            json_encode([
                'protocol_version' => '2',
                'method' => 'GET',
                'uri' => 'https://www.example.com/',
                'headers' => [
                    'Host' => ['www.example.com'],
                    'foo' => ['bar'],
                ],
                'body' => 'YmVlcg==',
            ]),
            $json,
        );

        self::assertSame('beer', (string) $request->getBody());
    }
}
