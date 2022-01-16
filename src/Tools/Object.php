<?php

namespace Tools;

use ReflectionClass;

class Object
{
    public static function getConstantes ($class_name) {
        $oClass = new ReflectionClass($class_name);
        return $oClass->getConstants();
    }

    public static function hasMethod ($class_name, $method) {
        $oClass = new ReflectionClass($class_name);
        return $oClass->hasMethod($method);
    }
}