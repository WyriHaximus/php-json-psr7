<?php

declare(strict_types=1);

namespace WyriHaximus;

use Ancarda\Psr7\StringStream\ReadOnlyStringStream;
use Cake\Utility\Hash;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use React\Http\Io\UploadedFile;
use RingCentral\Psr7\Request;
use RingCentral\Psr7\Response;
use RingCentral\Psr7\ServerRequest;

use function base64_decode;
use function base64_encode;
use function count;
use function is_string;
use function json_decode;
use function json_encode;
use function ksort;

use const JSON_THROW_ON_ERROR;

function psr7_response_json_encode(ResponseInterface $response): string
{
    return json_encode(psr7_response_encode($response), JSON_THROW_ON_ERROR);
}

/** @return array{protocol_version: string, status_code: int, reason_phrase: string, headers: array<string, mixed>, body: string} */
function psr7_response_encode(ResponseInterface $response): array
{
    /** @phpstan-ignore return.type */
    return ['protocol_version' => $response->getProtocolVersion(), 'status_code' => $response->getStatusCode(), 'reason_phrase' => $response->getReasonPhrase(), 'headers' => sort_headers($response->getHeaders()), 'body' => base64_encode((string) $response->getBody())];
}

/** @throws NotAnEncodedResponseException */
function psr7_response_json_decode(string $json): ResponseInterface
{
    /** @var array{protocol_version: string, status_code: int, reason_phrase: string, headers: array<string, mixed>, body: string} $jsonArray */
    $jsonArray = json_decode(
        json: $json,
        associative: true,
        flags: JSON_THROW_ON_ERROR,
    );

    return psr7_response_decode($jsonArray);
}

/**
 * @param array{protocol_version: string, status_code: int, reason_phrase: string, headers: array<string, mixed>, body: string} $json
 *
 * @throws NotAnEncodedResponseException
 */
function psr7_response_decode(array $json): ResponseInterface
{
    $properties = [
        'protocol_version' => 'string',
        'status_code' => 'integer',
        'reason_phrase' => 'string',
        'headers' => 'array',
        'body' => 'string',
    ];

    validate_array($json, $properties, NotAnEncodedResponseException::class);

    $json['body'] = base64_decode($json['body'], true);
    if (! is_string($json['body'])) {
        throw new NotAnEncodedRequestException($json, 'body');
    }

    return new Response(
        $json['status_code'],
        $json['headers'],
        new ReadOnlyStringStream($json['body']),
        $json['protocol_version'],
        $json['reason_phrase'],
    );
}

function psr7_request_json_encode(RequestInterface $request): string
{
    return json_encode(psr7_request_encode($request), JSON_THROW_ON_ERROR);
}

/** @return array{protocol_version: string, method: string, uri: string, headers: array<string, mixed>, body: string} */
function psr7_request_encode(RequestInterface $request): array
{
    /** @phpstan-ignore return.type */
    return ['protocol_version' => $request->getProtocolVersion(), 'method' => $request->getMethod(), 'uri' => (string) $request->getUri(), 'headers' => sort_headers($request->getHeaders()), 'body' => base64_encode((string) $request->getBody())];
}

/** @throws NotAnEncodedRequestException */
function psr7_request_json_decode(string $json): RequestInterface
{
    /** @var array{protocol_version: string, method: string, uri: string, headers: array<string, mixed>, body: string} $jsonArray */
    $jsonArray = json_decode(
        json: $json,
        associative: true,
        flags: JSON_THROW_ON_ERROR,
    );

    return psr7_request_decode($jsonArray);
}

/**
 * @param array{protocol_version: string, method: string, uri: string, headers: array<string, mixed>, body: string} $json
 *
 * @throws NotAnEncodedRequestException
 */
function psr7_request_decode(array $json): RequestInterface
{
    $properties = [
        'protocol_version' => 'string',
        'method' => 'string',
        'uri' => 'string',
        'headers' => 'array',
        'body' => 'string',
    ];

    validate_array($json, $properties, NotAnEncodedRequestException::class);

    $json['body'] = base64_decode($json['body'], true);
    if (! is_string($json['body'])) {
        throw new NotAnEncodedRequestException($json, 'body');
    }

    return new Request(
        $json['method'],
        $json['uri'],
        $json['headers'],
        new ReadOnlyStringStream($json['body']),
        $json['protocol_version'],
    );
}

function psr7_uploaded_file_json_encode(UploadedFileInterface $uploadedFile): string
{
    return json_encode(psr7_uploaded_file_encode($uploadedFile), JSON_THROW_ON_ERROR);
}

/** @return array{filename: ?string, media_type: ?string, error: int, size: ?int, stream: string} */
function psr7_uploaded_file_encode(UploadedFileInterface $uploadedFile): array
{
    return ['filename' => $uploadedFile->getClientFilename(), 'media_type' => $uploadedFile->getClientMediaType(), 'error' => $uploadedFile->getError(), 'size' => $uploadedFile->getSize(), 'stream' => base64_encode((string) $uploadedFile->getStream())];
}

/** @throws NotAnEncodedUploadedFileException */
function psr7_uploaded_file_json_decode(string $json): UploadedFileInterface
{
    /** @var array{stream: string, size: int, error: int, filename: string, media_type: string} $jsonArray */
    $jsonArray = json_decode(
        json: $json,
        associative: true,
        flags: JSON_THROW_ON_ERROR,
    );

    return psr7_uploaded_file_decode($jsonArray);
}

/**
 * @param array{stream: string, size: int, error: int, filename: string, media_type: string} $json
 *
 * @throws NotAnEncodedUploadedFileException
 */
function psr7_uploaded_file_decode(array $json): UploadedFileInterface
{
    $properties = [
        'stream' => 'string',
        'size' => ['integer', 'NULL'],
        'error' => 'integer',
        'filename' => ['string', 'NULL'],
        'media_type' => ['string', 'NULL'],
    ];

    validate_array($json, $properties, NotAnEncodedUploadedFileException::class);

    $json['stream'] = base64_decode($json['stream'], true);
    if (! is_string($json['stream'])) {
        throw new NotAnEncodedServerRequestException($json, 'stream');
    }

    return new UploadedFile(
        new ReadOnlyStringStream($json['stream']),
        $json['size'],
        $json['error'],
        $json['filename'],
        $json['media_type'],
    );
}

function psr7_server_request_json_encode(ServerRequestInterface $request): string
{
    return json_encode(psr7_server_request_encode($request), JSON_THROW_ON_ERROR);
}

/** @return array{protocol_version: string, method: string, uri: string, query_params: array<string, mixed>, cookie_params: array<string, mixed>, server_params: array<string, mixed>, headers: array<string, mixed>, attributes: array<string, mixed>, body: string, parsed_body: (array<mixed>|object|null), files: array<string, array{stream: string, size: int, error: int, filename: string, media_type: string}>} */
function psr7_server_request_encode(ServerRequestInterface $request): array
{
    /** @var array<string, UploadedFileInterface> $files */
    $files                    = Hash::flatten($request->getUploadedFiles());
    $json                     = [];
    $json['protocol_version'] = $request->getProtocolVersion();
    $json['method']           = $request->getMethod();
    $json['uri']              = (string) $request->getUri();
    $json['query_params']     = $request->getQueryParams();
    $json['cookie_params']    = $request->getCookieParams();
    $json['server_params']    = $request->getServerParams();
    $json['headers']          = sort_headers($request->getHeaders());
    $json['attributes']       = $request->getAttributes();
    $json['body']             = base64_encode((string) $request->getBody());
    $json['parsed_body']      = $request->getParsedBody();
    $json['files']            = [];
    foreach ($files as $key => $file) {
        $json['files'][$key] = psr7_uploaded_file_encode($file);
    }

    /** @phpstan-ignore return.type */
    return $json;
}

/**
 * @throws NotAnEncodedServerRequestException
 * @throws NotAnEncodedUploadedFileException
 */
function psr7_server_request_json_decode(string $json): ServerRequestInterface
{
    /** @var array{protocol_version: string, method: string, uri: string, query_params: array<string, mixed>, cookie_params: array<string, mixed>, server_params: array<string, mixed>, headers: array<string, mixed>, attributes: array<string, mixed>, body: string, parsed_body: (array<mixed>|object|null), files: array<string, array{stream: string, size: int, error: int, filename: string, media_type: string}>} $decodedJson */
    $decodedJson = json_decode($json, true);

    return psr7_server_request_decode($decodedJson);
}

/**
 * @param array{protocol_version: string, method: string, uri: string, query_params: array<string, mixed>, cookie_params: array<string, mixed>, server_params: array<string, mixed>, headers: array<string, mixed>, attributes: array<string, mixed>, body: string, parsed_body: (array<mixed>|object|null), files: array<string, array{stream: string, size: int, error: int, filename: string, media_type: string}>} $json
 *
 * @throws NotAnEncodedServerRequestException
 * @throws NotAnEncodedUploadedFileException
 */
function psr7_server_request_decode(array $json): ServerRequestInterface
{
    $properties = [
        'protocol_version' => 'string',
        'method' => 'string',
        'uri' => 'string',
        'query_params' => 'array',
        'cookie_params' => 'array',
        'server_params' => 'array',
        'headers' => 'array',
        'attributes' => 'array',
        'body' => 'string',
        'parsed_body' => ['array', 'object', 'NULL'],
        'files' => 'array',
    ];

    validate_array($json, $properties, NotAnEncodedServerRequestException::class);

    $json['body'] = base64_decode($json['body'], true);
    if (! is_string($json['body'])) {
        throw new NotAnEncodedServerRequestException($json, 'body');
    }

    $request = new ServerRequest(
        $json['method'],
        $json['uri'],
        $json['headers'],
        new ReadOnlyStringStream($json['body']),
        $json['protocol_version'],
        $json['server_params'],
    )->
        withParsedBody($json['parsed_body'])->
        withUploadedFiles($json['files'])->
        withQueryParams($json['query_params'])->
        withCookieParams($json['cookie_params']);

    foreach ($json['attributes'] as $key => $value) {
        $request = $request->withAttribute($key, $value);
    }

    if (count($json['files']) > 0) {
        foreach ($json['files'] as $key => $file) {
            $json['files'][$key] = psr7_uploaded_file_decode($file);
        }

        $json['files'] = Hash::expand($json['files']);
        $request       = $request->withUploadedFiles($json['files']);
    }

    return $request;
}

/**
 * @param array<array<string>> $headers
 *
 * @return array<array<string>>
 */
function sort_headers(array $headers): array
{
    ksort($headers);

    return $headers;
}
