<?php declare(strict_types=1);

namespace WyriHaximus;

use Exception;

final class NotAnEncodedServerRequestException extends Exception
{
    public function __construct($json, $field)
    {
        parent::__construct('"' . \json_encode($json) . '" is not an encoded PSR-7 server request, field "' . $field . '" is missing');
    }
}
