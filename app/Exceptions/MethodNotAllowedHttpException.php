<?php

namespace Exceptions;

class MethodNotAllowedHttpException extends \Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct('HTTP Method is Not Allowed: ' . $message, $code, $previous);
    }
}
