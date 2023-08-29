<?php

declare(strict_types=1);

namespace WyriHaximus;

use Ancarda\Psr7\StringStream\ReadOnlyStringStream;
use Cake\Utility\Hash;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use React\Http\Io\UploadedFile;

use function base64_encode;
use function count;
use function Safe\base64_decode;
use function Safe\json_decode;
use function Safe\json_encode;
use function Safe\ksort;

function psr7_response_json_encode(ResponseInterface $response): string
{
    return json_encode(psr7_response_encode($response));
}

/**
 * @return array{protocol_version: string, status_code: int, reason_phrase: string, headers: array<array-key, array<array-key, mixed>>, body: string}
 */
function psr7_response_encode(ResponseInterface $response): array
{
    $json                     = [];
    $json['protocol_version'] = $response->getProtocolVersion();
    $json['status_code']      = $response->getStatusCode();
    $json['reason_phrase']    = $response->getReasonPhrase();
    $json['headers']          = sort_headers($response->getHeaders());
    $json['body']             = base64_encode((string) $response->getBody());

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
 * @param array{protocol_version: string, status_code: int, reason_phrase: string, headers: array<array-key, array<array-key, mixed>>, body: string} $json
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

    return new Response(
        $json['status_code'],
        $json['headers'],
        new ReadOnlyStringStream(base64_decode($json['body'], true)),
        $json['protocol_version'],
        $json['reason_phrase']
    );
}

function psr7_request_json_encode(RequestInterface $request): string
{
    return json_encode(psr7_request_encode($request));
}

/**
 * @return array{protocol_version: string, method: string, uri: string, headers: array<array-key, array<array-key, mixed>>, body: string}
 */
function psr7_request_encode(RequestInterface $request): array
{
    $json                     = [];
    $json['protocol_version'] = $request->getProtocolVersion();
    $json['method']           = $request->getMethod();
    $json['uri']              = (string) $request->getUri();
    $json['headers']          = sort_headers($request->getHeaders());
    $json['body']             = base64_encode((string) $request->getBody());

    return $json;
}

/**
 * @throws NotAnEncodedRequestException
 */
function psr7_request_json_decode(string $json): RequestInterface
{
    return psr7_request_decode(json_decode($json, true));
}

/**
 * @param array{protocol_version: string, method: string, uri: string, headers: array<array-key, array<array-key, mixed>>, body: string} $json
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

    return new Request(
        $json['method'],
        $json['uri'],
        $json['headers'],
        new ReadOnlyStringStream(base64_decode($json['body'], true)),
        $json['protocol_version']
    );
}

function psr7_uploaded_file_json_encode(UploadedFileInterface $uploadedFile): string
{
    return json_encode(psr7_uploaded_file_encode($uploadedFile));
}

/**
 * @return array{stream: string, size: ?int, error: int, filename: ?string, media_type: ?string}
 */
function psr7_uploaded_file_encode(UploadedFileInterface $uploadedFile): array
{
    $json               = [];
    $json['filename']   = $uploadedFile->getClientFilename();
    $json['media_type'] = $uploadedFile->getClientMediaType();
    $json['error']      = $uploadedFile->getError();
    $json['size']       = $uploadedFile->getSize();
    $json['stream']     = base64_encode((string) $uploadedFile->getStream());

    return $json;
}

/**
 * @throws NotAnEncodedUploadedFileException
 */
function psr7_uploaded_file_json_decode(string $json): UploadedFileInterface
{
    return psr7_uploaded_file_decode(json_decode($json, true));
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

    /**
     * @psalm-suppress InternalMethod
     */
    return new UploadedFile(
        new ReadOnlyStringStream(base64_decode($json['stream'], true)),
        $json['size'],
        $json['error'],
        $json['filename'],
        $json['media_type']
    );
}

function psr7_server_request_json_encode(ServerRequestInterface $request): string
{
    return json_encode(psr7_server_request_encode($request));
}

/**
 * @return array{attributes: array<array-key, mixed>, body: string, cookie_params: array<array-key, mixed>, files: array<array-key, (array{error: int, filename: (null|string), media_type: (null|string), size: (int|null), stream: string}|mixed)>, headers: array<array-key, array<mixed>>, method: string, parsed_body: (array<array-key, mixed>|object|null), protocol_version: string, query_params: array<array-key, mixed>, server_params: array<array-key, mixed>, uri: string}
 */
function psr7_server_request_encode(ServerRequestInterface $request): array
{
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
    $json['files']            = $request->getUploadedFiles();
    $json['files']            = Hash::flatten($json['files']);
    foreach ($json['files'] as $key => $file) {
        $json['files'][$key] = psr7_uploaded_file_encode($file);
    }

    return $json;
}

/**
 * @throws NotAnEncodedServerRequestException
 * @throws NotAnEncodedUploadedFileException
 */
function psr7_server_request_json_decode(string $json): ServerRequestInterface
{
    return psr7_server_request_decode(json_decode($json, true));
}

/**
 * @param array{attributes: array<array-key, mixed>, body: string, cookie_params: array<array-key, mixed>, files: array<array-key, (array{error: int, filename: (null|string), media_type: (null|string), size: (int|null), stream: string}|mixed)>, headers: array<array-key, array<mixed>>, method: string, parsed_body: (array<array-key, mixed>|object|null), protocol_version: string, query_params: array<array-key, mixed>, server_params: array<array-key, mixed>, uri: string} $json
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

    /** @psalm-suppress ImplicitToStringCast */
    $request = (new ServerRequest(
        $json['method'],
        $json['uri'],
        $json['headers'],
        new ReadOnlyStringStream(base64_decode($json['body'], true)),
        $json['protocol_version'],
        $json['server_params']
    ))->
        withParsedBody($json['parsed_body'])->
        withUploadedFiles($json['files'])->
        withQueryParams($json['query_params'])->
        withCookieParams($json['cookie_params']);

    foreach ($json['attributes'] as $key => $value) {
        $request = $request->withAttribute($key, $value);
    }

    if (count($json['files']) > 0) {
        /** @var array{stream: string, size: int, error: int, filename: string, media_type: string} $file */
        foreach ($json['files'] as $key => $file) {
            $json['files'][$key] = psr7_uploaded_file_decode($file);
        }

        $json['files'] = Hash::expand($json['files']);
        $request       = $request->withUploadedFiles($json['files']);
    }

    return $request;
}

/**
 * @param array<array<string, mixed>> $headers
 *
 * @return array<array<string, mixed>>
 */
function sort_headers(array $headers): array
{
    ksort($headers);

    return $headers;
}
