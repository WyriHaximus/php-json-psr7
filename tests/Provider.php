<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\ServerRequest as GuzzleServerRequest;
use function GuzzleHttp\Psr7\stream_for as guzzle_stream_for;
use React\Http\Io\UploadedFile;
use RingCentral\Psr7\Request as RinCentralRequest;
use RingCentral\Psr7\Response as RinCentralResponse;
use RingCentral\Psr7\ServerRequest as RinCentralServerRequest;
use function RingCentral\Psr7\stream_for as ring_central_stream_for;
use WyriHaximus;

/**
 * @internal
 */
final class Provider
{
    public function request(): iterable
    {
        yield 'ringcentral' => [
            new RinCentralRequest(
                'GET',
                'https://www.example.com/',
                [
                    'foo' => 'bar',
                ],
                'beer',
                '2.0'
            ),
        ];

        yield 'guzzle' => [
            new GuzzleRequest(
                'GET',
                'https://www.example.com/',
                [
                    'foo' => 'bar',
                ],
                'beer',
                '2'
            ),
        ];
    }

    public function response(): iterable
    {
        yield 'ringcentral' => [
            new RinCentralResponse(
                200,
                [
                    'foo' => 'bar',
                ],
                'beer',
                '2.0',
                'awesome'
            ),
        ];

        yield 'guzzle' => [
            new GuzzleResponse(
                200,
                [
                    'foo' => 'bar',
                ],
                'beer',
                '2',
                'awesome'
            ),
        ];
    }

    public function serverRequest(): iterable
    {
        foreach ($this->uploadedFileWaterBottle() as $wbk => $waterBottle) {
            $waterBottle = $waterBottle[0];
            foreach ($this->uploadedFileBeerBottle() as $bbk => $beerBottle) {
                $beerBottle = $beerBottle[0];
                $files = [
                    'root' => [
                        'water' => $waterBottle,
                        'beer' => $beerBottle,
                    ],
                ];
                $time = \time();

                $request = (new RinCentralServerRequest(
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
                ]);

                yield 'ringcentral_w_' . $wbk . '_b_' . $bbk => [$request, $time, $waterBottle, $beerBottle];

                $request = (new GuzzleServerRequest(
                    'GET',
                    'https://www.example.com/?foo=bar',
                    [
                        'foo' => 'bar',
                    ],
                    'beer',
                    '2',
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
                ]);

                yield 'guzzle_w_' . $wbk . '_b_' . $bbk => [$request, $time, $waterBottle, $beerBottle];
            }
        }
    }

    public function uploadedFileWaterBottle(): iterable
    {
        yield 'ringcentral' => [
            new UploadedFile(ring_central_stream_for('Water'), 5, \UPLOAD_ERR_OK, 'water.bottle', 'earth/liquid'),
        ];

        yield 'guzzle' => [
            new UploadedFile(guzzle_stream_for('Water'), 5, \UPLOAD_ERR_OK, 'water.bottle', 'earth/liquid'),
        ];
    }

    public function uploadedFileBeerBottle(): iterable
    {
        yield 'ringcentral' => [
            new UploadedFile(ring_central_stream_for('Dark Horizon 5'), 14, \UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid'),
        ];

        yield 'guzzle' => [
            new UploadedFile(guzzle_stream_for('Dark Horizon 5'), 14, \UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid'),
        ];
    }
}
