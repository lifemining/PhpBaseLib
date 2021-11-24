<?php

namespace Lifemining\PhpBaseLib\Tools;


class Operator
{
    /**
     * Criteria checker
     *
     * @param string $sValue1 - the value to be compared
     * @param string $sOperator - the operator
     * @param string $sValue2 - the value to test against
     * @return boolean - criteria met/not met
     */
    public static function runEval($sValue1, $sOperator, $sValue2)
    {
        switch ($sOperator) {
            case '<':
                return $sValue1 < $sValue2;
                break;
            case '<=':
                return $sValue1 <= $sValue2;
                break;
            case '>':
                return $sValue1 > $sValue2;
                break;
            case '>=':
                return $sValue1 >= $sValue2;
                break;
            case '==':
                return $sValue1 == $sValue2;
                break;
            case '===':
                return $sValue1 === $sValue2;
                break;
            case '!=':
                return $sValue1 != $sValue2;
                break;
            case '!==':
                return $sValue1 !== $sValue2;
                break;
            default:
                return false;
        }
        return false;
    }

    /**
     * Criteria string
     *
     * @param string $sOperator - the operator
     * @return string
     */
    public static function CriteriaString($sOperator)
    {
        switch ($sOperator) {
            case '<':
                return 'less than';
                break;
            case '<=':
                return 'less than or equal';
                break;
            case '>':
                return 'greater than';
                break;
            case '>=':
                return 'greater than or equal';
                break;
            case '==':
                return 'equal';
                break;
            case '===':
                return 'xequal';
                break;
            case '!=':
                return 'different';
                break;
            case '!==':
                return 'xdifferent';
                break;
            default:
                return false;
        }
        return false;
    }    

}
