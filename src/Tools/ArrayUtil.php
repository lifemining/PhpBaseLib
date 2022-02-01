<?php

namespace Lifemining\PhpBaseLib\Tools;

class ArrayUtil {

    /**
     * check if array as string has key
     *
     * @param array $array
     *
     * @return bool
     */
    public static function is2D (array $array)
    {
        return !is_numeric(join('', array_keys($array)));
    }

    /**
     * retire n éléments à la fin du tableau
     * @param array $array
     * @param type $n
     */
    public static function nPop (array $array, $n) {
        for (; $n > 0 && count($array); $n--) {
            array_pop($array);
        }
        return $array ;
    }

    /**
     * efface dans un tableau les clé fournies
     * @param array $array le tableau à purger
     * @param array $purge clé à effacer
     * @return array
     */
    public static function purge(array $array, array $purge) {
        foreach ($purge as $key) {
            if (isset($array[$key])) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * efface dans un tableau les clé fournies
     * @param array $array le tableau à purger
     * @param array $purge clé à effacer
     * @return array
     */
    public static function purgeRec(array $array, array $purge) {

        foreach ($array as $key => $value) {
            if (in_array($key, $purge)) {
                unset($array[$key]);
            } else if (is_array($array[$key])) {
                $array[$key] = self::purgeRec($array[$key], $purge);
            }
        }
        return $array;
    }

    /**
     * efface dans un tableau les valeurs fournies
     * @param array $array le tableau à purger
     * @param array $purge les valeurs à effacer
     * @return array
     */
    public static function purgeValues (array $array, array $purge) {
        foreach ($purge as $value) {
            $array = self::purge($array, array_keys($array, $value));
        }
        return $array;
    }

    /**
     * garde dans un tableau les clé fournies et efface le reste
     * @param array $array le tableau à purger
     * @param array $keep les clés à garder
     * @return array
     */
    public static function keep(array $array, array $keep) {
        foreach ($array as $key => $value) {
            if (!in_array($key, $keep)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * merge deux tableau en conservant les clés
     * si la clé est déjà définie dans le premier tableau elle sera écrasée
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function mergeWithKey(array $array1, array $array2) {
        foreach ($array2 as $key => $value) {
            $array1[$key] = $value;
        }
        return $array1;
    }

    /**
     * affiche un tableau sous le bon formatage HTML
     * @param array $array
     */
    public static function dump($array) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    /**
     * join + chaque valeur se voit ajouter un préfix et un suffix
     * @param array $aTab
     * @param string $sep
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public static function joinWithPrefixAndSuffix(array $aTab, $sep = '', $prefix = '', $suffix = '') {
        foreach ($aTab as $k => $v) {
            $aTab[$k] = $prefix . $v . $suffix;
        }
        return join($sep, $aTab);
    }

    /**
     * join key and value with a separator
     * @param array $aTab
     * @param string $sSep
     * @return array
     */
    public static function joinKeyValue (array $aTab, $sSep = '=') {
        $aTmp = array();
        foreach ($aTab as $sKey => $sValue) {
            $aTmp[] = $sKey.$sSep.$sValue;
        }
        return $aTmp;
    }

    /**
     * cherche récurcivement toutes les clés égale à $needle
     * et retourne un tableau avec toutes les valeurs qui lui sont associées
     * @param string $needle
     * @param array $array
     * @return array
     */
    public static function getAllValuesForKey($needle, array $array) {
        $result = array();
        foreach ($array as $key => $value) {
            if ($key === $needle) {
                $result[] = $value;
            }
            if (is_array($value)) {
                $result = array_merge($result, self::getAllValuesForKey($needle, $value));
            } else if (is_object($value) && isset($value->{$needle})) {
                $result[] = $value->{$needle};
            }
        }
        return $result;
    }

    public static function makeArray($mValue) {
        if (!is_array($mValue)) {
            return array($mValue);
        }
        return $mValue;
    }

    /**
     * empile les résultats $value pour une clé $key
     * @param array $array
     * @param mixed $key
     * @param mixed $value
     * @return array
     */
    public static function empile($array, $key, $value) {
        if (isset($array[$key])) {
            if (!is_array($array[$key])) {
                $array[$key] = array($array[$key]);
            }
            $array[$key][] = $value;
        } else {
            $array[$key] = $value;
        }
        return $array;
    }

    /**
     *
     * @param array $aArray
     * @param array $aDimensions
     * @param mixed $mDefault
     * @return mixed
     */
    public static function getInArray (array $aArray, array $aDimensions, $mDefault = null) {
        if ($aDimensions && count($aDimensions)) {
            $key = array_shift($aDimensions);
            if (isset($aArray[$key])) {
                if (count($aDimensions)) {
                    return self::getInArray($aArray[$key], $aDimensions);
                } else {
                    return $aArray[$key];
                }
            }
            return $mDefault ;
        } else {
            return $aArray;
        }
    }

    /**
     * transforme un arbre en tableau de path
     * @param array $aTree
     * @param string $sPath
     * @param string $sSep
     * @param mixed $iMaxLevel (null or integer)
     * @return array
     */
    public static function treeToPath (array $aTree, $sPath = '', $sSep = '/', $iMaxLevel = null) {
        $aTmp = array();
        if ($iMaxLevel !== null) {
            $iMaxLevel--;
        }
        foreach ($aTree as $sKey => $mValue) {
            if (is_array($mValue) && $iMaxLevel !== 0) {
                $aTmp = array_merge($aTmp, self::treeToPath($mValue, $sPath.$sKey.$sSep, $sSep, $iMaxLevel));
            } else {
                $aTmp[$sPath.$sKey] = $mValue;
            }
        }
        return $aTmp;
    }


    public static function export2D (array $aArray, array $options = array()) {
        $options = array_merge(array(
            'prefix'    => "'",
            'suffix'    => "'",
            'eof'       => "\n",
            'tab'       => "\t",
            'nTab'      => 1,
        ), $options);
        return "array("
            . $options['eof']
            . Str::repeatNstr($options['tab'], $options['nTab'])
            . self::joinWithPrefixAndSuffix(
                $aArray,
                ",".$options['eof'].Str::repeatNstr($options['tab'], $options['nTab']),
                $options['prefix'],
                $options['suffix']
            )
            . $options['eof']
            . Str::repeatNstr($options['tab'], $options['nTab'] - 1)
            . ")";
    }


    public static function export (array $aArray, array $options = array()) {
        $options = array_merge(array(
            'eof'       => "\n",
            'tab'       => "\t",
            'nTab'      => 1,
        ), $options);
        return str_replace(
            array("=> \n  ",    $options['eof']),
            array("=> ",        $options['eof'].Str::repeatNstr($options['tab'], $options['nTab'])),
            var_export($aArray, true)
        );
    }

    /**
     * format key of tab
     *
     * @param array  $aArray
     * @param string $sFormat
     * @param string $sKeyReplace
     *
     * @return array
     */
    public static function formatKey (array $aArray, $sFormat, $sKeyReplace = '{KEY}') {
        $aTmp = array();
        foreach ($aArray as $sKey => $mValue) {
            $aTmp[str_replace($sKeyReplace, $sKey, $sFormat)] = $mValue;
        }
        return $aTmp;
    }

    /**
     * Sort array recursively.
     *
     * @param mixed $mArray
     *
     * @return bool ksort result.
     */
    public static function ksortRecursive(&$mArray)
    {
        if (is_array($mArray) === true) {
            array_walk($mArray, array('self', 'ksortRecursive'));

            return ksort($mArray);
        }

        return false;
    }

    /**
     * Compare two arrays recursively. Returns true if arrays are equals.
     * Ignore keys order.
     *
     * @param array $aArray1
     * @param array $aArray2
     *
     * @return bool
     */
    public static function areEqualsRecursive(array $aArray1, array $aArray2)
    {
        static::ksortRecursive($aArray1);
        static::ksortRecursive($aArray2);

        return $aArray1 === $aArray2;
    }

    /**
     * replace all string in value and key of array recursivly
     *
     * @param       $mSearch
     * @param       $mReplace
     * @param array $aToReplace
     *
     * @return array
     */
    public static function replaceStringRec ($mSearch, $mReplace, array $aToReplace) {
        $aReplace = array();
        foreach ($aToReplace as $mKey => $mValue) {
            if (is_string($mKey)) {
                $mKey = str_replace($mSearch, $mReplace, $mKey);
            }
            if (is_array($mValue)) {
                $mValue = self::replaceStringRec($mSearch, $mReplace, $mValue);
            } else if (is_string($mValue)) {
                $mValue = str_replace($mSearch, $mReplace, $mValue);
            }
            $aReplace[$mKey] = $mValue;
        }
        return $aReplace;
    }
}
