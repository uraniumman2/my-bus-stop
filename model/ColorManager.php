<?php

namespace model;
// THIS IS SINGLETON MAZAFAKA
class ColorManager
{
    private $aMatches = array();
    private $aColors = array('#ee1d23', '#f58220', '#2e3092', '#00aeef', '#00a54f', '#a54586', '#6c6d70','#188500' , '#990000');
    private $iAvailableColor = 0;
    private static $oInstance;
    const INITIAL_COLOR_VAL = 0;

    private function __constructor()
    {
    }

    public static function getInstance()
    {
        if (empty(self::$oInstance)) {
            self::$oInstance = new ColorManager();
        }
        return self::$oInstance;
    }

    public function setColor($key)
    {
        $this->aMatches[$key] = $this->getNextColor();
    }

    public function getColor($key)
    {
        return isset($this->aMatches[$key]) ? $this->aMatches[$key] : false;
    }

    public function clearMatches()
    {
        $this->aMatches = array();
        $this->iAvailableColor = self::INITIAL_COLOR_VAL;
    }

    private function getNextColor()
    {
        return $this->aColors[($this->iAvailableColor++) % count($this->aColors)];
    }

    public function getColorMatches()
    {
        return $this->aMatches;
    }
}