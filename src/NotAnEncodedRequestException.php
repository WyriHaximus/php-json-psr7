<?php

declare(strict_types=1);

namespace WyriHaximus;

use Exception;

use function Safe\json_encode;

final class NotAnEncodedRequestException extends Exception
{
    /**
     * @param mixed $json
     */
    public function __construct($json, string $field)
    {
        parent::__construct('"' . json_encode($json) . '" is not an encoded PSR-7 request, field "' . $field . '" is missing');
    }
}
