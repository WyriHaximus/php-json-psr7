<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use RingCentral\Psr7\Request;
use WyriHaximus;

/**
 * @internal
 */
final class RequestEncodeTest extends TestCase
{
    /**
     * @dataProvider \WyriHaximus\Tests\Provider::request
     */
    public function testSuccess(RequestInterface $request): void
    {
        $json = WyriHaximus\psr7_request_encode($request);
        self::assertSame(
            [
                'protocol_version' => '2',
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
            ],
            $json
        );

        self::assertSame('beer', (string)$request->getBody());
    }
}
