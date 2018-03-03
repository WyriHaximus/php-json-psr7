<?php

namespace WyriHaximus;

use Exception;

final class NotAnEncodedRequestException extends Exception
{
    public function __construct($json, $field)
    {
        parent::__construct('"' . json_encode($json) . '"" is not an encoded PSR-7 request, field "' . $field . '" is missing');
    }
}
