<?php

namespace WyriHaximus;

use Exception;

final class NotAnEncodedUploadedFileException extends Exception
{
    public function __construct($json, $field)
    {
        parent::__construct('"' . json_encode($json) . '" is not an encoded PSR-7 uploaded file, field "' . $field . '" is missing');
    }
}
