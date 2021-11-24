<?php
/**
 * Created by PhpStorm.
 * User: mdelettrez
 * Date: 10/04/17
 * Time: 17:14
 */

namespace Lifemining\PhpBaseLib\Tools;


class Cast
{
    public static function boolval ($mValue) {
        return filter_var($mValue, FILTER_VALIDATE_BOOLEAN);
    }
}