<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use RingCentral\Psr7\ServerRequest;
use WyriHaximus;

/**
 * @internal
 */
final class ServerRequestEncodeTest extends TestCase
{
    /**
     * @dataProvider \WyriHaximus\Tests\Provider::serverRequest
     */
    public function testSuccess(ServerRequestInterface $request, int $time, UploadedFileInterface $waterBottle, UploadedFileInterface $beerBottle): void
    {
        $json = WyriHaximus\psr7_server_request_encode($request);
        self::assertSame(
            [
                'protocol_version' => '2',
                'method' => 'GET',
                'uri' => 'https://www.example.com/?foo=bar',
                'query_params' => [
                    'foo' => 'bar',
                ],
                'cookie_params' => [
                    'remember_me' => 'yes',
                ],
                'server_params' => [
                    'REQUEST_TIME' => $time,
                    'QUERY_STRING' => 'foo=bar',
                ],
                'headers' => [
                    'Host' => [
                        'www.example.com',
                    ],
                    'foo' => [
                        'bar',
                    ],
                ],
                'attributes' => [
                    'beer' => 'Dark Horizon 5',
                ],
                'body' => 'YmVlcg==',
                'parsed_body' => [
                    'Dark Horizon 5',
                ],
                'files' => [
                    'root.water' => [
                        'filename' => 'water.bottle',
                        'media_type' => 'earth/liquid',
                        'error' => 0,
                        'size' => 5,
                        'stream' => 'V2F0ZXI=',
                    ],
                    'root.beer' => [
                        'filename' => 'beer.bottle',
                        'media_type' => 'earth/liquid',
                        'error' => 0,
                        'size' => 14,
                        'stream' => 'RGFyayBIb3Jpem9uIDU=',
                    ],
                ],
            ],
            $json
        );

        self::assertSame('Water', (string)$waterBottle->getStream());
        self::assertSame('Dark Horizon 5', (string)$beerBottle->getStream());
        self::assertSame('beer', (string)$request->getBody());
    }
}
