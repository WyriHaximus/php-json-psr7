<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use React\Http\Io\UploadedFile;
use RingCentral\Psr7\ServerRequest;
use WyriHaximus;
use function RingCentral\Psr7\stream_for;

/**
 * @internal
 */
final class ServerRequestJsonEncodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $waterBottle = new UploadedFile(stream_for('Water'), 5, UPLOAD_ERR_OK, 'water.bottle', 'earth/liquid');
        $beerBottle = new UploadedFile(stream_for('Dark Horizon 5'), 14, UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid');
        $files = [
            'root' => [
                'water' => $waterBottle,
                'beer' => $beerBottle,
            ],
        ];
        $time = \time();
        $request = (new ServerRequest(
            'GET',
            'https://www.example.com/?foo=bar',
            [
                'foo' => 'bar',
            ],
            'beer',
            '2.0',
            [
                'REQUEST_TIME' => $time,
                'QUERY_STRING' => 'foo=bar',
            ]
        ))->
            withAttribute('beer', 'Dark Horizon 5')->
            withParsedBody('Dark Horizon 5')->
            withUploadedFiles($files)->
            withQueryParams([
                'foo' => 'bar',
            ])->
            withCookieParams([
                'remember_me' => 'yes',
            ])
        ;

        $json = WyriHaximus\psr7_server_request_json_encode($request);
        self::assertSame(
            \json_encode([
                'protocol_version' => '2.0',
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
                'parsed_body' => 'Dark Horizon 5',
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
            ]),
            $json
        );
    }
}
