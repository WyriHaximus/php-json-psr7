<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\ServerRequest as GuzzleServerRequest;
use function GuzzleHttp\Psr7\stream_for as guzzle_stream_for;
use Nyholm\Psr7\Request as NyholmRequest;
use Nyholm\Psr7\Response as NyholmResponse;
use Nyholm\Psr7\ServerRequest as NyholmServerRequest;
use Nyholm\Psr7\Stream as NyholmStream;
use React\Http\Io\UploadedFile;
use RingCentral\Psr7\Request as RinCentralRequest;
use RingCentral\Psr7\Response as RinCentralResponse;
use RingCentral\Psr7\ServerRequest as RinCentralServerRequest;
use function RingCentral\Psr7\stream_for as ring_central_stream_for;
use Slim\Psr7\Factory\StreamFactory as SlimStreamFactory;
use Slim\Psr7\Headers as SlimHeaders;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Response as SlimResponse;
use Slim\Psr7\Uri as SlimUri;
use Zend\Diactoros\Request as ZendDiactorosRequest;
use Zend\Diactoros\Response as ZendDiactorosResponse;
use Zend\Diactoros\ServerRequest as ZendDiactorosServerRequest;
use Zend\Diactoros\StreamFactory as ZendDiactorosStreamFactory;

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
                '2'
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

        yield 'nyholm' => [
            new NyholmRequest(
                'GET',
                'https://www.example.com/',
                [
                    'foo' => 'bar',
                ],
                'beer',
                '2'
            ),
        ];

        yield 'zend-diactoros' => [
            (new ZendDiactorosRequest(
                'https://www.example.com/',
                'GET',
                (new ZendDiactorosStreamFactory())->createStream('beer'),
                [
                    'foo' => 'bar',
                ]
            ))->withProtocolVersion('2'),
        ];

        yield 'slim' => [
            (new SlimRequest(
                'GET',
                new SlimUri('https', 'www.example.com'),
                new SlimHeaders([
                    'foo' => 'bar',
                ]),
                [],
                [],
                (new SlimStreamFactory())->createStream('beer')
            ))->withProtocolVersion('2'),
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
                '2',
                'OK'
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
                'OK'
            ),
        ];

        yield 'nyholm' => [
            new NyholmResponse(
                200,
                [
                    'foo' => 'bar',
                ],
                'beer',
                '2',
                'OK'
            ),
        ];

        yield 'zend-diactoros' => [
            (new ZendDiactorosResponse(
                (new ZendDiactorosStreamFactory())->createStream('beer'),
                200,
                [
                    'foo' => 'bar',
                ]
            ))->withProtocolVersion('2'),
        ];

        yield 'slim' => [
            (new SlimResponse(
                200,
                new SlimHeaders([
                    'foo' => 'bar',
                ]),
                (new SlimStreamFactory())->createStream('beer')
            ))->withProtocolVersion('2'),
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
                    '2',
                    [
                        'REQUEST_TIME' => $time,
                        'QUERY_STRING' => 'foo=bar',
                    ]
                ))->
                    withAttribute('beer', 'Dark Horizon 5')->
                    withParsedBody(['Dark Horizon 5'])->
                    withUploadedFiles($files)->
                    withQueryParams([
                        'foo' => 'bar',
                    ])->
                    withCookieParams([
                        'remember_me' => 'yes',
                    ])
                ;

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
                    withParsedBody(['Dark Horizon 5'])->
                    withUploadedFiles($files)->
                    withQueryParams([
                        'foo' => 'bar',
                    ])->
                    withCookieParams([
                        'remember_me' => 'yes',
                    ])
                ;

                yield 'guzzle_w_' . $wbk . '_b_' . $bbk => [$request, $time, $waterBottle, $beerBottle];

                $request = (new NyholmServerRequest(
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
                    withParsedBody(['Dark Horizon 5'])->
                    withUploadedFiles($files)->
                    withQueryParams([
                        'foo' => 'bar',
                    ])->
                    withCookieParams([
                        'remember_me' => 'yes',
                    ])
                ;

                yield 'nyholm_w_' . $wbk . '_b_' . $bbk => [$request, $time, $waterBottle, $beerBottle];

                $request = (new ZendDiactorosServerRequest(
                    [
                        'REQUEST_TIME' => $time,
                        'QUERY_STRING' => 'foo=bar',
                    ],
                    $files,
                    'https://www.example.com/?foo=bar',
                    'GET',
                    (new ZendDiactorosStreamFactory())->createStream('beer'),
                    [
                        'foo' => 'bar',
                    ]
                ))->
                    withAttribute('beer', 'Dark Horizon 5')->
                    withParsedBody(['Dark Horizon 5'])->
                    withQueryParams([
                        'foo' => 'bar',
                    ])->
                    withCookieParams([
                        'remember_me' => 'yes',
                    ])->
                    withProtocolVersion('2')
                ;

                yield 'zend-diactoros_w_' . $wbk . '_b_' . $bbk => [$request, $time, $waterBottle, $beerBottle];

                $request = (new SlimRequest(
                    'GET',
                    new SlimUri('https', 'www.example.com', null, '/', 'foo=bar'),
                    new SlimHeaders([
                        'foo' => 'bar',
                    ]),
                    [],
                    [
                        'REQUEST_TIME' => $time,
                        'QUERY_STRING' => 'foo=bar',
                    ],
                    (new SlimStreamFactory())->createStream('beer'),
                    $files
                ))->
                    withAttribute('beer', 'Dark Horizon 5')->
                    withParsedBody(['Dark Horizon 5'])->
                    withQueryParams([
                        'foo' => 'bar',
                    ])->
                    withCookieParams([
                        'remember_me' => 'yes',
                    ])->
                    withProtocolVersion('2')
                ;

                yield 'slim_w_' . $wbk . '_b_' . $bbk => [$request, $time, $waterBottle, $beerBottle];
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

        yield 'nyholm' => [
            new UploadedFile(NyholmStream::create('Water'), 5, \UPLOAD_ERR_OK, 'water.bottle', 'earth/liquid'),
        ];

        yield 'zend-diactoros' => [
            new UploadedFile((new ZendDiactorosStreamFactory())->createStream('Water'), 5, \UPLOAD_ERR_OK, 'water.bottle', 'earth/liquid'),
        ];

        yield 'slim' => [
            new UploadedFile((new SlimStreamFactory())->createStream('Water'), 5, \UPLOAD_ERR_OK, 'water.bottle', 'earth/liquid'),
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

        yield 'nyholm' => [
            new UploadedFile(NyholmStream::create('Dark Horizon 5'), 14, \UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid'),
        ];

        yield 'zend-diactoros' => [
            new UploadedFile((new ZendDiactorosStreamFactory())->createStream('Dark Horizon 5'), 14, \UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid'),
        ];

        yield 'slim' => [
            new UploadedFile((new SlimStreamFactory())->createStream('Dark Horizon 5'), 14, \UPLOAD_ERR_OK, 'beer.bottle', 'earth/liquid'),
        ];
    }
}
