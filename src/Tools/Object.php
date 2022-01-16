<?php

namespace Lifemining\PhpBaseLib\Tools;

use ReflectionClass;

class Object
{
    public static function exists ($className) {
        if (!class_exists($className)) {
            trigger_error($className.' is not loaded');
        }
    }
    
    public static function getConstantes ($className) {
        self::exists($className);
        $oClass = new ReflectionClass($className);
        return $oClass->getConstants();
    }

    public static function hasMethod ($className, $method) {
        self::exists($className);
        $oClass = new ReflectionClass($className);
        return $oClass->hasMethod($method);
    }

    public static function getMethods ($className) {
        self::exists($className);
        $oClass     = new ReflectionClass($className);
        $methods    = $oClass->getMethods();
        $result     = array();
        foreach ($methods as $method) {
            $result[] = $method->name;
        }
        return $result;
    }
}