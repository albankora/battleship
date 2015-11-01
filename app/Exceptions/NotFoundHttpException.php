<?php

namespace Exceptions;

class NotFoundHttpException extends \Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct('Not Found HTTP Exception', $code, $previous);
    }
}
