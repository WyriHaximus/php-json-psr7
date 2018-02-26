<?php

namespace WyriHaximus;

use Exception;

final class NotAnEncodedResponseException extends Exception
{
    public function __construct($json)
    {
        parent::__construct('"' . json_encode($json) . '"" is not an encoded Throwable or Exception');
    }
}
