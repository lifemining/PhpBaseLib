<?php

namespace Lifemining\PhpBaseLib\Tools;

/**
 * Class Regexp
 * Tools for Regexp
 *
 * @package Lifemining\PhpBaseLib\Tools
 */
class Regexp
{
    /**
     * list of special char
     * @var array
     */
    public static $specialChar = array(
        '+', '.', '?', '*', '$', '^',
        '{', '}', '[', ']', '(', ')',
        '\\'
    );

    /**
     * escape char if is a special char
     *
     * @param string $char
     * @return string
     */
    public static function escapeIfNeeded ($char)
    {
        if (in_array($char, self::$specialChar)) {
            return '\\'.$char;
        }
        return $char;
    }
}