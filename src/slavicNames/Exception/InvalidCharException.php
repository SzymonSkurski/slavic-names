<?php

namespace SlavicNames\Exception;

use Exception;
use Throwable;

class InvalidCharException extends Exception
{
    public function __construct(string $char, $code = 0, Throwable $previous = null)
    {
        $message = 'invalid character: '.$char.', [a-z] only';
        parent::__construct($message, $code, $previous);
    }
}