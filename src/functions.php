<?php declare(strict_types=1);

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

function psr7_response_json_encode(ResponseInterface $response): string
{
    return json_try_encode(psr7_response_encode($response));
}

function psr7_response_encode(ResponseInterface $response): array
{
    $json = [];
    $json['protocol_version'] = $response->getProtocolVersion();
    $json['status_code'] = $response->getStatusCode();
    $json['reason_phrase'] = $response->getReasonPhrase();
    $json['headers'] = sort_headers($response->getHeaders());
    $json['body'] = \base64_encode((string)$response->getBody());

    return $json;
}

/**
 * @throws NotAnEncodedResponseException
 */
function psr7_response_json_decode(string $json): ResponseInterface
{
    return psr7_response_decode(json_try_decode($json, true));
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

    validate_array($json, $properties, NotAnEncodedResponseException::class);

    return new Response(
        $json['status_code'],
        $json['headers'],
        new ReadOnlyStringStream(\base64_decode($json['body'], true)),
        $json['protocol_version'],
        $json['reason_phrase']
    );
}

function psr7_request_json_encode(RequestInterface $request): string
{
    return json_try_encode(psr7_request_encode($request));
}

function psr7_request_encode(RequestInterface $request): array
{
    $json = [];
    $json['protocol_version'] = $request->getProtocolVersion();
    $json['method'] = $request->getMethod();
    $json['uri'] = (string)$request->getUri();
    $json['headers'] = sort_headers($request->getHeaders());
    $json['body'] = \base64_encode((string)$request->getBody());

    return $json;
}

/**
 * @throws NotAnEncodedRequestException
 */
function psr7_request_json_decode(string $json): RequestInterface
{
    return psr7_request_decode(json_try_decode($json, true));
}

/**
 * @throws NotAnEncodedRequestException
 */
function psr7_request_decode(array $json): RequestInterface
{
    $properties = [
        'protocol_version',
        'method',
        'uri',
        'headers',
        'body',
    ];

    validate_array($json, $properties, NotAnEncodedRequestException::class);

    return new Request(
        $json['method'],
        $json['uri'],
        $json['headers'],
        new ReadOnlyStringStream(\base64_decode($json['body'], true)),
        $json['protocol_version']
    );
}

function psr7_uploaded_file_json_encode(UploadedFileInterface $uploadedFile): string
{
    return json_try_encode(psr7_uploaded_file_encode($uploadedFile));
}

function psr7_uploaded_file_encode(UploadedFileInterface $uploadedFile): array
{
    $json = [];
    $json['filename'] = $uploadedFile->getClientFilename();
    $json['media_type'] = $uploadedFile->getClientMediaType();
    $json['error'] = $uploadedFile->getError();
    $json['size'] = $uploadedFile->getSize();
    $json['stream'] = \base64_encode((string)$uploadedFile->getStream());

    return $json;
}

/**
 * @throws NotAnEncodedUploadedFileException
 */
function psr7_uploaded_file_json_decode(string $json): UploadedFileInterface
{
    return psr7_uploaded_file_decode(json_try_decode($json, true));
}

/**
 * @throws NotAnEncodedUploadedFileException
 */
function psr7_uploaded_file_decode(array $json): UploadedFileInterface
{
    $properties = [
        'stream',
        'size',
        'error',
        'filename',
        'media_type',
    ];

    validate_array($json, $properties, NotAnEncodedUploadedFileException::class);

    return new UploadedFile(
        new ReadOnlyStringStream(\base64_decode($json['stream'], true)),
        $json['size'],
        $json['error'],
        $json['filename'],
        $json['media_type']
    );
}

function psr7_server_request_json_encode(ServerRequestInterface $request): string
{
    return json_try_encode(psr7_server_request_encode($request));
}

function psr7_server_request_encode(ServerRequestInterface $request): array
{
    $json = [];
    $json['protocol_version'] = $request->getProtocolVersion();
    $json['method'] = $request->getMethod();
    $json['uri'] = (string)$request->getUri();
    $json['query_params'] = $request->getQueryParams();
    $json['cookie_params'] = $request->getCookieParams();
    $json['server_params'] = $request->getServerParams();
    $json['headers'] = sort_headers($request->getHeaders());
    $json['attributes'] = $request->getAttributes();
    $json['body'] = \base64_encode((string)$request->getBody());
    $json['parsed_body'] = $request->getParsedBody();
    $json['files'] = $request->getUploadedFiles();
    $json['files'] = Hash::flatten($json['files']);
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
    return psr7_server_request_decode(json_try_decode($json, true));
}

/**
 * @throws NotAnEncodedServerRequestException
 * @throws NotAnEncodedUploadedFileException
 */
function psr7_server_request_decode(array $json): ServerRequestInterface
{
    $properties = [
        'protocol_version',
        'method',
        'uri',
        'query_params',
        'cookie_params',
        'server_params',
        'headers',
        'attributes',
        'body',
        'parsed_body',
        'files',
    ];

    validate_array($json, $properties, NotAnEncodedServerRequestException::class);

    $request = (new ServerRequest(
        $json['method'],
        $json['uri'],
        $json['headers'],
        new ReadOnlyStringStream(\base64_decode($json['body'], true)),
        $json['protocol_version'],
        $json['server_params']
    ))->
        withParsedBody($json['parsed_body'])->
        withUploadedFiles($json['files'])->
        withQueryParams($json['query_params'])->
        withCookieParams($json['cookie_params'])
    ;

    foreach ($json['attributes'] as $key => $value) {
        $request = $request->withAttribute($key, $value);
    }

    if (\count($json['files']) > 0) {
        foreach ($json['files'] as $key => $file) {
            $json['files'][$key] = psr7_uploaded_file_decode($file);
        }
        $json['files'] = Hash::expand($json['files']);
        $request = $request->withUploadedFiles($json['files']);
    }

    return $request;
}

function sort_headers(array $headers): array
{
    \ksort($headers);

    return $headers;
}
