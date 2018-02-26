<?php

namespace WyriHaximus;

use Psr\Http\Message\ResponseInterface;
use RingCentral\Psr7\Response;

function psr7_response_json_encode(ResponseInterface $response): string
{
    return json_encode(psr7_response_encode($response));
}

function psr7_response_encode(ResponseInterface $response): array
{
    $json = [];
    $json['protocol_version'] = $response->getProtocolVersion();
    $json['status_code'] = $response->getStatusCode();
    $json['reason_phrase'] = $response->getReasonPhrase();
    $json['headers'] = $response->getHeaders();
    $json['body'] = $response->getBody()->getContents();

    return $json;
}

/**
 * @throws NotAnEncodedResponseException
 */
function psr7_response_json_decode(string $json): ResponseInterface
{
    return psr7_response_decode(json_decode($json, true));
}

/**
 * @throws NotAnEncodedResponseException
 */
function psr7_response_decode(array $json): ResponseInterface
{
    $properties = [
        'protocol_version',
        'status_code',
        'reason_phrase',
        'headers',
        'body',
    ];

    foreach ($properties as $property) {
        if (!isset($json[$property])) {
            throw new NotAnEncodedResponseException($json);
        }
    }

    return new Response(
        $json['status_code'],
        $json['headers'],
        $json['body'],
        $json['protocol_version'],
        $json['reason_phrase']
    );
}
