<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use WyriHaximus;

final class RequestEncodeTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(Provider::class, 'request')]
    public function success(RequestInterface $request): void
    {
        $json = WyriHaximus\psr7_request_encode($request);
        self::assertSame(
            [
                'protocol_version' => '2',
                'method' => 'GET',
                'uri' => 'https://www.example.com/',
                'headers' => [
                    'Host' => ['www.example.com'],
                    'foo' => ['bar'],
                ],
                'body' => 'YmVlcg==',
            ],
            $json,
        );

        self::assertSame('beer', (string) $request->getBody());
    }
}
