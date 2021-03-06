<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UploadedFileInterface;
use WyriHaximus;

use function Safe\json_encode;
use function time;

use const UPLOAD_ERR_OK;

/**
 * @internal
 */
final class ServerRequestJsonDecodeTest extends TestCase
{
    public function testSuccess(): void
    {
        $time = time();
        $json = json_encode([
            'protocol_version' => '2',
            'method' => 'GET',
            'uri' => 'https://www.example.com/?foo=bar',
            'query_params' => ['foo' => 'bar'],
            'cookie_params' => ['remember_me' => 'yes'],
            'server_params' => [
                'REQUEST_TIME' => $time,
                'QUERY_STRING' => 'foo=bar',
            ],
            'headers' => [
                'Host' => ['www.example.com'],
                'foo' => ['bar'],
            ],
            'attributes' => ['beer' => 'Dark Horizon 5'],
            'body' => 'YmVlcg==',
            'parsed_body' => ['Dark Horizon 5'],
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
        ]);

        $request = WyriHaximus\psr7_server_request_json_decode($json);
        self::assertSame('2', $request->getProtocolVersion());
        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://www.example.com/?foo=bar', (string) $request->getUri());
        self::assertSame([
            'Host' => ['www.example.com'],
            'foo' => ['bar'],
        ], $request->getHeaders());
        self::assertSame('beer', (string) $request->getBody());
        self::assertSame('beer', (string) $request->getBody());
        self::assertSame([
            'REQUEST_TIME' => $time,
            'QUERY_STRING' => 'foo=bar',
        ], $request->getServerParams());
        self::assertSame(['foo' => 'bar'], $request->getQueryParams());
        self::assertSame(['remember_me' => 'yes'], $request->getCookieParams());
        self::assertSame(['beer' => 'Dark Horizon 5'], $request->getAttributes());

        $files = $request->getUploadedFiles();
        self::assertCount(2, $files['root']);

        self::assertInstanceOf(UploadedFileInterface::class, $files['root']['water']);
        self::assertSame(5, $files['root']['water']->getSize());
        self::assertSame('water.bottle', $files['root']['water']->getClientFilename());
        self::assertSame('earth/liquid', $files['root']['water']->getClientMediaType());
        self::assertSame('Water', (string) $files['root']['water']->getStream());
        self::assertSame('Water', (string) $files['root']['water']->getStream());
        self::assertSame(UPLOAD_ERR_OK, $files['root']['water']->getError());

        self::assertInstanceOf(UploadedFileInterface::class, $files['root']['beer']);
        self::assertSame(14, $files['root']['beer']->getSize());
        self::assertSame('beer.bottle', $files['root']['beer']->getClientFilename());
        self::assertSame('earth/liquid', $files['root']['beer']->getClientMediaType());
        self::assertSame('Dark Horizon 5', (string) $files['root']['beer']->getStream());
        self::assertSame('Dark Horizon 5', (string) $files['root']['beer']->getStream());
        self::assertSame(UPLOAD_ERR_OK, $files['root']['beer']->getError());
    }

    public function testFailure(): void
    {
        self::expectException(WyriHaximus\NotAnEncodedServerRequestException::class);
        self::expectExceptionMessage('"[]" is not an encoded PSR-7 server request, field "protocol_version" is missing');

        WyriHaximus\psr7_server_request_json_decode('[]');
    }
}
