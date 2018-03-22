<?php
namespace model;

class BoundaryManager {
    private $iMaxX = 0;
    private $iMinX = 3000;
    private $iMaxY = 0;
    private $iMinY = 3000;
    private static $oInstance;
    const INITIAL_COLOR_VAL = 0;

    private function __constructor() { }

    public static function getInstance() {
        if (empty(self::$oInstance)) {
            self::$oInstance = new BoundaryManager();
        }
        return self::$oInstance;
    }


    public function clearBoundaries() {
        $this->iMinX = 0;
        $this->iMaxX = 3000;
        $this->iMinY = 0;
        $this->iMaxY = 3000;
    }

    public function getBoundaries()
    {
        return array(
            'min_x' => $this->iMinX,
            'max_x' => $this->iMaxX,
            'min_y' => $this->iMinY,
            'max_y' => $this->iMaxY
        );
    }

    public function getCropBoundaries($iOffsetX, $iOffsetY) {
        $iCropMinX = (($this->iMinX - $iOffsetX) < 0) ? 0 : ($this->iMinX - $iOffsetX);
        $iCropMaxX = (($this->iMaxX + $iOffsetX) > CANVAS_WIDTH) ? CANVAS_WIDTH : ($this->iMaxX + $iOffsetX);
        $iCropMinY = (($this->iMinY - $iOffsetY) < 0) ? 0 : ($this->iMinY - $iOffsetY);
        $iCropMaxY = (($this->iMaxY + $iOffsetY) > CANVAS_HEIGHT) ? CANVAS_HEIGHT : ($this->iMaxY + $iOffsetY);

        return array(
            $iCropMinX,
            $iCropMaxX,
            $iCropMinY,
            $iCropMaxY
        );
    }

    public function compareCoords($iCoordX, $iCoordY)
    {
        // Сравнения оси X
        if ($iCoordX > $this->iMaxX)
            $this->iMaxX = $iCoordX;
        elseif($iCoordX < $this->iMinX)
            $this->iMinX = $iCoordX;

        // Сравнения оси Y
        if ($iCoordY > $this->iMaxY)
            $this->iMaxY = $iCoordY;
        elseif($iCoordY < $this->iMinY)
            $this->iMinY = $iCoordY;
    }

}