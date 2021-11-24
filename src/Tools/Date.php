<?php

namespace Lifemining\PhpBaseLib\Tools;

/**
 * Class Date
 *
 * @package Lifemining\PhpBaseLib\Tools
 */
class Date
{
    /**
     * @param $sDate
     *
     * @return bool
     */
    public static function hasTimeZone ($sDate)
    {
        return preg_match('/\d{2}:\d{2}:\d{2}/', $sDate);
    }
}