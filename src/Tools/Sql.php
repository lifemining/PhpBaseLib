<?php

namespace Lifemining\PhpBaseLib\Tools;

class Sql
{
    static $operateurs = array(
        '=',
        '!=',
        '>',
        '<',
        '>=',
        '<=',
        '<>',
        '<=>',
        '~*',
        '!~*',
        'IN',
        'NOT IN',
        'IS NULL',
        'IS NOT NULL',
        'BETWEEN',
        'NOT BETWEEN',
        'NOT LIKE',
        'NOT ILIKE',
        'LIKE',
        'ILIKE',
    );

    static $functions = array(
        'now()',
        'date_trunc(',
        'length(',
    );

    public static function hasFunction ($sHasFunc) {
        foreach (self::$functions as $sFunc) {
            if (strpos($sHasFunc, $sFunc) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function isOperateur ($sOp) {
        return in_array($sOp, self::$operateurs);
    }

    public static function getOperateurFromStr ($str) {
        foreach (array_reverse(self::$operateurs) as $op) {
            if (strpos($str, $op) !== false) {
                return $op;
            }
        }
        return false;
    }

    public static function strHasOperateur($str, array $aSkip = array())
    {
        foreach (self::$operateurs as $op) {
            if (in_array($op, $aSkip)) {
                continue;
            }
            if (strpos($str, $op) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function strHasArrayOperateur($str)
    {
        if (is_string($str) && strpos($str, 'IN') !== false) {
            return true;
        }
        return false;
    }

    public static function isArrayOperateur($op)
    {
        return (in_array($op, array('IN', 'NOT IN')));
    }

    public static function addDefaultOperateurIfMissing ($str) {
        if (!self::strHasOperateur($str)) {
            $str .= ' = ?';
        }
        return $str;
    }
}