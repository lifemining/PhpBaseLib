<?php

namespace Lib;


class Options
{

    protected $aConfig      = array();
    protected $aRawOptions  = array();
    protected $aOptions     = array();
    protected $aErrors      = array();
    protected $aNotices     = array();

    // @todo : add translator

    // @todo : add error & notice template

    public function __construct (array $aConfig)
    {
        // @todo : add options params to this class in second argument
        $this->aConfig = $aConfig;
    }

    public function reset ($bRestConfig = false) {
        // @todo : use reflexion class to catch all protected and private var and put it on default value
        if ($bRestConfig) {
            $this->aConfig  = array();
        }
        $this->aRawOptions  = array();
        $this->aOptions     = array();
        $this->aErrors      = array();
        $this->aNotices     = array();
        return $this;
    }

    public function setOptions (array $aRawOptions) {
        $this->reset();
        $this->aRawOptions = $aRawOptions;
        $this->parse();
        return $this;
    }

    public function getAllowedOptionsKey () {
        return array_keys($this->aConfig);
    }

    public function getConfig ($sType = null, $sOption = null) {
        if ($sOption) {
            if (isset($this->aConfig[$sOption])) {
                if ($sType) {
                    if (isset($this->aConfig[$sOption][$sType])) {
                        return $this->aConfig[$sOption][$sType];
                    }
                } else {
                    return $this->aConfig[$sOption];
                }
            }
        } else {
            if ($sType) {
                $aConfig = array();
                foreach ($this->aConfig as $sOption => $aConfigOption) {
                    if (isset($aConfigOption[$sType])) {
                        $aConfig[$sOption] = $aConfigOption[$sType];
                    }
                }
                return $aConfig;
            } else {
                return $this->aConfig;
            }
        }
        return null;
    }

    protected function parse () {
        $aRawOptions = $this->aRawOptions;
        foreach ($this->aConfig as $sOption => $aConfigOption) {
            // get the value of the option
            if (isset($aConfigOption['noValue']) && $aConfigOption['noValue']) {
                // ckeck only if is present
                if (isset($aRawOptions[$sOption])) {
                    $this->aOptions[$sOption] = true;
                } else {
                    $this->aOptions[$sOption] = false;
                }
            } else if (isset($aRawOptions[$sOption])) {
                // take it form options
                $this->aOptions[$sOption] = $aRawOptions[$sOption];
            } else if (isset($aConfigOption['default'])) {
                // take default
                $this->aOptions[$sOption] = $aConfigOption['default'];
            } else {
                // no option & no default => REQUIRED
                $this->aErrors[$sOption] = 'option \''.$sOption.'\' is required to '.$aConfigOption['help'];
                continue;
            }

            // check alias
            if (
                isset($aConfigOption['alias'])
                && is_array($aConfigOption['alias'])
                && isset($aConfigOption['alias'][$this->aOptions[$sOption]])
            ) {
                $this->aOptions[$sOption] = $aConfigOption['alias'][$this->aOptions[$sOption]];
            }

            // convert to array if needed
            if (
                isset($aConfigOption['separator'])
                && is_string($aConfigOption['separator'])
                && strlen($aConfigOption['separator'])
                && is_string($this->aOptions[$sOption])
            ) {
                $this->aOptions[$sOption] = explode($aConfigOption['separator'], $this->aOptions[$sOption]);
            }

            // apply filters
            if (isset($aConfigOption['filters']) && is_array($aConfigOption['filters'])) {
                foreach ($aConfigOption['filters'] as $filter) {
                    $this->aOptions[$sOption] = array_map($filter, $this->aOptions[$sOption]);
                }
            }

            // @todo : check type & cast

            // check allowed values
            if (isset($aConfigOption['accept']) && is_array($aConfigOption['accept'])) {
                if (is_array($this->aOptions[$sOption])) {
                    foreach ($this->aOptions[$sOption] as $sOptionToCheck) {
                        if (!in_array($sOptionToCheck, $aConfigOption['accept'])) {
                            // value not allowed
                            $this->aErrors[$sOption] = 'value \''.$sOptionToCheck
                                .'\' is not allowed for option \''.$sOption
                                .'\' must be '.join(' | ', $this->getOptionAccept($sOption));
                        }
                    }
                } else if (!in_array($this->aOptions[$sOption], $aConfigOption['accept'])) {
                    // value not allowed
                    $this->aErrors[$sOption] = 'value \''.$this->aOptions[$sOption]
                        .'\' is not allowed for option \''.$sOption
                        .'\' must be '.join(' | ', $this->getOptionAccept($sOption));
                }
            }

            // remove from temp rawOptions to keep options not matched
            unset($aRawOptions[$sOption]);
        }

        // all options wich are not catched
        foreach ($aRawOptions as $sOption => $mValue) {
            $this->aNotices[$sOption] = 'Unknown option \''.$sOption.'\'';
        }

        return $this;
    }

    protected function getOptionAccept ($sOption, $bWithAlias = true)
    {
        $aAccept = $this->getConfig('accept', $sOption);
        if (is_array($aAccept) && $bWithAlias) {
            $aAlias = $this->getConfig('alias', $sOption);
            if (is_array($aAlias)) {
                $aAccept = array_merge(array_keys($aAlias), $aAccept);
            }
        }
        return $aAccept;
    }

    public function hasErrors () {
        return count($this->aErrors);
    }

    public function getErrors () {
        return $this->aErrors;
    }

    public function hasNotices () {
        return count($this->aNotices);
    }

    public function getNotices () {
        return $this->aNotices;
    }

    public function __isset($sOption)
    {
        return isset($this->aOptions[$sOption]);
    }

    public function __get($sOption)
    {
        if (isset($this->aOptions[$sOption])) {
            return $this->aOptions[$sOption];
        }
        return null;
    }

    public function getOptions () {
        return $this->aOptions;
    }
}