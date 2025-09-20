<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use WyriHaximus;

final class ResponseEncodeTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(Provider::class, 'response')]
    public function success(ResponseInterface $response): void
    {
        $json = WyriHaximus\psr7_response_encode($response);
        self::assertSame(
            [
                'protocol_version' => '2',
                'status_code' => 200,
                'reason_phrase' => 'OK',
                'headers' => [
                    'foo' => ['bar'],
                ],
                'body' => 'YmVlcg==',
            ],
            $json,
        );

        self::assertSame('beer', (string) $response->getBody());
    }
}
