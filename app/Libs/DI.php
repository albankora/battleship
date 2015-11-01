<?php

namespace Libs;

use ReflectionClass;
use ReflectionParameter;

class DI
{

    private static $dependencies = [];

    public static function bind($key, $value, $arguments = null)
    {
        self::addToMap($key, array(
            "value" => $value,
            "type" => "class",
            "arguments" => $arguments
        ));
    }

    public static function singleton($key, $value, $arguments = null)
    {
        self::addToMap($key, array(
            "value" => $value,
            "type" => "singleton",
            "instance" => null,
            "arguments" => $arguments
        ));
    }

    public static function getInstanceOf($className)
    {
        if (!class_exists($className)) {
            throw new \Exception("DI: missing class '" . $className . "'.");
        }

        $reflection = new ReflectionClass($className);

        if (!$reflection->isInstantiable()) {
            throw new \Exception("[$className] is not instantiable");
        }

        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();

        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependencies[] = static::findTypeHint($parameter);
        }

        $arguments = [];
        foreach ($dependencies as $arg) {
            $arguments[] = static::loadDependency($arg);
        }

        // creating an instance of the class
        if($arguments === null || count($arguments) == 0) {
            $obj = new $className;
        } else {
            $obj = $reflection->newInstanceArgs($arguments);
        }

        return $obj;
    }

    public static function findTypeHint($refParam)
    {
        $export = ReflectionParameter::export(
            array(
                $refParam->getDeclaringClass()->name,
                $refParam->getDeclaringFunction()->name
            ),
            $refParam->name,
            true
        );

        return preg_replace('/.*?(\w+)\s+\$' . $refParam->name . '.*/', '\\1', $export);
    }

    public static function loadDependency($dependency)
    {
        foreach(static::$dependencies as $k => $r){
            if(strpos($k, $dependency) !== false){
                return call_user_func($r['value']);
            }
        }
    }

    public static function mapValue($key, $value)
    {
        self::addToMap($key, array(
            "value" => $value,
            "type" => "value"
        ));
    }

    private static function addToMap($key, $obj)
    {
        self::$dependencies[$key] = $obj;
    }
}
