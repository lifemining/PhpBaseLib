<?php

namespace Lifemining\PhpBaseLib\Tools\Regexp;
use Zend\Stdlib\Exception\BadMethodCallException;

/**
 * Class RegCapture
 *
 * @package Lifemining\PhpBaseLib\Tools\Regexp
 */
class RegCapture
{

    /**
     * @var array
     */
    protected $aMatches = array();

    /**
     * RegCapture constructor.
     *
     * @param string $sString
     * @param string $sPattern
     */
    public function __construct($sString, $sPattern)
    {
        //preg_match_all($sPattern, $sString, $this->aMatches);
        preg_match($sPattern, $sString, $this->aMatches);
    }

    /**
     * test if var name exists
     *
     * @param $sName
     *
     * @return bool
     */
    public function __isset($sName)
    {
        return (isset($this->aMatches[$sName]) && strlen($this->aMatches[$sName]));
    }

    /**
     * get var by name
     *
     * @param $sName
     *
     * @return null|string
     */
    public function __get($sName)
    {
        if ($this->__isset($sName)) {
            return $this->aMatches[$sName];
        }
        return null;
    }

    /**
     * alias for call get or isset
     *
     * @param string $sMethod
     * @param mixed $arguments
     *
     * @return bool|null|string
     * @throws BadMethodCallException
     */
    public function __call($sMethod, $arguments)
    {
        if (strpos($sMethod, 'get') === 0) {
            return $this->__get(lcfirst(substr($sMethod, 3)));
        } elseif (strpos($sMethod, 'has') === 0) {
            return $this->__isset(lcfirst(substr($sMethod, 3)));
        }
        throw new BadMethodCallException(sprintf('unrecognized method \'%s\' in class \'%s\'', $sMethod, __CLASS__));
    }

    /**
     * get all result
     *
     * @param bool $bIndexed
     *
     * @return array
     */
    public function getMatches($bIndexed = true)
    {
        if ($bIndexed) {
            $aTmp = array();
            foreach ($this->aMatches as $mKey => $mValue) {
                if (is_string($mKey)) {
                    $aTmp[$mKey] = $mValue;
                }
            }
            return $aTmp;
        }
        return $this->aMatches;
    }
}