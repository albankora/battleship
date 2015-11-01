<?php

namespace Exceptions;

class FileNotFoundException extends \Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct('File Not Found: ' . $message, $code, $previous);
    }
}