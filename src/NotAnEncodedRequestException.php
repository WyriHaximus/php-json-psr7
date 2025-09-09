<?php

declare(strict_types=1);

namespace WyriHaximus;

use Exception;

use function json_encode;

final class NotAnEncodedRequestException extends Exception
{
    public function __construct(mixed $json, string $field)
    {
        parent::__construct('"' . json_encode($json) . '" is not an encoded PSR-7 request, field "' . $field . '" is missing');
    }
}
