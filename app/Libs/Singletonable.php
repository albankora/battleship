<?php

namespace Libs;

trait Singletonable
{
    protected static $instance;

    final public static function getInstance()
    {
        return isset(static::$instance) ? static::$instance : static::$instance = new static;
    }

    final private function __construct()
    {
    }

    final private function __clone()
    {
    }
}