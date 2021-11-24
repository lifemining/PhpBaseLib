<?php
/**
 * Created by PhpStorm.
 * User: nemo
 * Date: 04/11/16
 * Time: 02:06
 */

namespace Lifemining\PhpBaseLib\Tools;


class Test
{
    public static function compare ($mValue, $sOperator, $mCompare) {
        switch ($sOperator) {
            case '=':
            case '==':
                return ($mValue == $mCompare);
            case '!=':
            case '<>':
                return ($mValue != $mCompare);
            case '!==':
                return ($mValue !== $mCompare);
            case '===':
                return ($mValue === $mCompare);
            case '>':
                return ($mValue > $mCompare);
            case '>=':
                return ($mValue >= $mCompare);
            case '<':
                return ($mValue < $mCompare);
            case '<=':
                return ($mValue <= $mCompare);
            case 'IN':
                return (is_array($mCompare) && in_array($mValue, $mCompare));
            case 'NOT IN':
                return (is_array($mCompare) && !in_array($mValue, $mCompare));
        }
        return null;
    }
}