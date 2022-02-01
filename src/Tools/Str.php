<?php

namespace Lifemining\PhpBaseLib\Tools;

use Lifemining\PhpBaseLib\Tools\ArrayUtil;
use Lifemining\PhpBaseLib\Tools\Number;

class Str {

    /**
     * replace space by underscore
     *
     * @param $string
     *
     * @return string
     */
    public static function snakize ($string)
    {
        return preg_replace('/\s+/', '_', strtolower($string));
    }

    public static function camelize($string, $bFirstUpper = true) {
        $string = implode('', array_map('ucfirst', array_map('strtolower', explode('_', $string))));
        if (!$bFirstUpper) {
            $string = lcfirst($string);
        }
        return $string;
    }

    public static function unCamelize($string, $sSep = '_') {
        return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1".$sSep."$2", $string));
    }

    public static function jsonRepare($json) {
        $tmp = explode(',', $json);
        $end_chaine = ArrayUtil::getEndValue($tmp);
        $nb_coma = self::getNbChar($end_chaine, '"');
        if (!Number::isPair($nb_coma)) {
            $json .= '"';
        }
        if (strpos($end_chaine, ':') === false) {
            $json .= ':""';
        }
        $nb_acolade_ouvrante = self::getNbChar($json, '{');
        $nb_acolade_fermante = self::getNbChar($json, '}');
        while ($nb_acolade_fermante < $nb_acolade_ouvrante) {
            $json .= '}';
            $nb_acolade_fermante++;
        }
        return $json;
    }

    public static function getNbChar($str, $char) {
        return strlen($str) - strlen(str_replace($char, "", $str));
    }

    public static function getNbLines($str, $offset = null) {
        if ($offset) {
            //list($str) = str_split($str, $offset);
            $str = substr($str, 0, $offset);
            if ($str === false) {
                return 0;
            }
        }
        $endLine = self::getCharEndLine($str, "\n");
        return strlen($str) - strlen(str_replace($endLine, "", $str)) + 1;
    }

    public static function addCharToEnd($str, $char) {
        if (!self::isLastChar($str, $char)) {
            return $str . $char;
        }
        return $str;
    }

    public static function delCharToEnd($str, $char) {
        if (self::isLastChar($str, $char)) {
            return substr($str, 0, -strlen($char));
        }
        return $str;
    }
    
    public static function addCharToStart($str, $char) {
        if (!self::isFirstChar($str, $char)) {
            return $char . $str;
        }
        return $str;
    }

    public static function delCharToStart($str, $char) {
        if (self::isFirstChar($str, $char)) {
            return substr($str, strlen($char));
        }
        return $str;
    }

    /**
     * return true si un des préfix est présent en début de chaine
     * @param string $str
     * @param array $prefix
     * @return boolean
     */
    public static function hasPrefix($str, $prefix = array()) {
        foreach ($prefix as $p) {
            if (strpos($str, $p) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * retourne le charactère de fin de ligne si trouvé
     * @param string $str la chaine dans laquelle chercher
     * @return null | char si aucun trouvé sinon retourne le charactère de fin de ligne
     */
    public static function getCharEndLine($str, $default = null) {
        foreach (array("\r\n", "\r", "\n") as $endLine) {
            if (strpos($str, $endLine) !== false) {
                return $endLine;
            }
        }
        return $default;
    }

    /**
     *
     * @param string $mixed
     * @param string $sep
     * @param number $char
     * @return string
     */
    public static function putSepEachChar($mixed, $sep = ' ', $char = 3) {
        $reg = '';
        for ($i = 0; $i < $char; $i++) {
            $reg .= '\d';
        }
        return preg_replace('/(-?\d+)(' . $reg . ')/', '$1' . $sep . '$2', strval($mixed));
        //$str = strval($mixed);
        //$tab = str_split ($str, $nb);
        //return join($sep, $tab);
    }

    /**
     * sépare les mots d'une chaine grâce au séparateur $sep et mets la première lettre de tous les mots en majuscules
     * @param string $str la chaîne à modifier
     * @param string $sep le séparateur des mots
     * @return string la chaîne modifiée
     */
    public static function ucFirstWord($str, $sep = ' ') {
        $result = '';
        if (strlen($str) > 0) {
            if (strpos($str, $sep) > 0) {
                $tab = explode($sep, $str);
                foreach ($tab as $row) {
                    $tmp[] = ucfirst($row);
                }
                $result = join($sep, $tmp);
            } else {
                $result = ucfirst($str);
            }
        }
        return $result;
    }

    /**
     * retourne la dernière lettre d'une chaîne
     * @param string $str la chaine à traiter
     * @return null si la chaine est vide sinon le dernier charactère
     */
    public static function getFirstChar($str) {
        $len = strlen($str);
        if ($len >= 1) {
            return $str[0];
        }
        return null;
    }

    /**
     * retourne la dernière lettre d'une chaîne
     * @param string $str la chaine à traiter
     * @return null si la chaine est vide sinon le dernier charactère
     */
    public static function getLastChar($str) {
        $len = strlen($str);
        if ($len) {
            return $str[$len - 1];
        }
        return null;
    }

    public static function isLastChar($str, $char) {
        return (self::getLastChar($str) == $char);
    }

    public static function isFirstChar($str, $char) {
        return (self::getFirstChar($str) == $char);
    }

    /**
     * enlève au début et à la fin de $str toutes les occurences de $char
     * @todo : remplacer par une expession régulière car il y'a un bug
     *         si la string contient au milieu plusieurs occurences d'affilé de $char
     *         alors elles seront remplacées que par une occurence.
     *         + permettre de prendre des caractères spéciaux, ex : \n | \t | \s
     * @param string $str
     * @param char $char
     * @return string
     */
    public static function trimChar($str, $char = ' ') {
        if (is_string($str)) {
            $tmp = explode($char, $str);
            foreach ($tmp as $key => $value) {
                if (strlen($value) == 0) {
                    unset($tmp[$key]);
                }
            }
            return implode($char, $tmp);
        }
        return $str;
    }

    /**
     * permet de savoir si une chaine contient du code html
     * @param string $str la chaine à tester
     * @return boolean true si la chaine comporte du code html sinon false
     */
    public static function isHtml($str) {
        return (strlen($str) !== strlen(strip_tags($str)));
        //return preg_match_all("/(<([\w]+)[^>]*>)(.*?)(<\/\\2>)/", $str, $matches) > 0;
    }

    /**
     * enlève le premier mot d'un chaine
     * les mots sont séparés par le séparateur sep
     * @param string $str
     * @param string $sep
     * @return string
     */
    public static function delFirstWord($str, $sep = ' ') {
        $tmp = explode($sep, $str);
        if (count($tmp) >= 1) {
            array_shift($tmp);
            return implode($sep, $tmp);
        }
        return '';
    }

    public static function delEndWord ($str, $sep = ' ') {
        return self::nDelEndWord($str, 1, $sep);
    }

    public static function nDelEndWord($str, $n, $sep = ' ') {
        return implode($sep, ArrayUtil::nPop(explode($sep, $str), $n));
    }

    public static function nKeepWord($str, $n, $sep = ' ') {
        $tmp = explode($sep, $str);
        if (count($tmp) > $n) {
            return implode($sep, ArrayUtil::nPop($tmp, (count($tmp) - $n)));
        }
        return $str;
    }

    /**
     * découpe une chaine avec plusieurs séparateurs
     * @param string $str
     * @param array $seps
     * @return array
     */
    public static function explodeMultiSeps($str, array $seps = array()) {
        if (count($seps)) {
            array_walk($seps, function (&$sep) {
                $sep = Regexp::escapeIfNeeded($sep);
            });
            //return explode($seps[0], str_replace($seps, $seps[0], $str));
            return preg_split("/(" . join($seps, "|") . ")/", $str);
        }
        return array($str);
    }

    /**
     * répète n fois la chaine de caractère
     * @param string $str
     * @param integer $n
     * @return string
     */
    public static function repeatNstr($str, $n) {
        $tmp = '';
        for ($i = 0; $i < $n; $i++) {
            $tmp .= $str;
        }
        return $tmp;
    }

    public static function usedInPhp ($sWord) {
        $aKeywords = array('__halt_compiler', 'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final', 'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or', 'print', 'private', 'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while', 'xor');
        return in_array(strtolower($sWord), $aKeywords);
    }

    public static function transformToBeUsedInPhp ($sWord, $sAdd = 's') {
        if (self::usedInPhp($sWord)) {
            return $sWord.$sAdd;
        }
        return $sWord;
    }

    public static function toArray ($str, $sSepItem = ',', $sSepValue = '=') {
        $aTmp = array();
        $aItems = explode($sSepItem, $str);
        foreach ($aItems as $sItem) {
            list ($key, $value) = explode($sSepValue, $sItem);
            $aTmp[$key] = $value;
        }
        return $aTmp;
    }

}
