<?php

namespace WyriHaximus\Tests;

use PHPUnit\Framework\TestCase;
use RingCentral\Psr7\Response;
use WyriHaximus;

final class ResponseJsonEncodeTest extends TestCase
{
    public function test()
    {
        $response = new Response(
            200,
            [
                'foo' => 'bar',
            ],
            'beer',
            '2.0',
            'awesome'
        );

        $json = WyriHaximus\psr7_response_json_encode($response);
        self::assertSame(
            json_encode([
                'protocol_version' => '2.0',
                'status_code' => 200,
                'reason_phrase' => 'awesome',
                'headers' => [
                    'foo' => [
                        'bar',
                    ],
                ],
                'body' => 'YmVlcg==',
            ]),
            $json
        );
    }
}
