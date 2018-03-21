<?php
namespace model;
// THIS IS SINGLETON MAZAFAKA
class ColorManager {
    private $aMatches = array();
    private $aColors = array('red', 'blue', 'green', 'yellow', 'purple', 'orange', 'darkblue', 'pink', 'brown', 'cyan');
    private $iAvailableColor = 0;
    private static $oInstance;
    const INITIAL_COLOR_VAL = 0;

    private function __constructor() { }

    public static function getInstance() {
        if (empty( self::$oInstance ) ) {
            self::$oInstance = new ColorManager();
        }
        return self::$oInstance;
    }

    public function setColor( $key ) {
        $this->aMatches[$key] = $this->getNextColor();
    }

    public function getColor( $key ) {
        return isset($this->aMatches[$key]) ? $this->aMatches[$key] : false;
    }

    public function clearMatches() {
        $this->aMatches = array();
        $this->iAvailableColor = self::INITIAL_COLOR_VAL;
    }

    private function getNextColor() {
        return $this->aColors[($this->iAvailableColor++) % count($this->aColors)];
    }

    public static function getColorMatches() {
        return self::aMatches;
    }
}