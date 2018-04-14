<?php

namespace model;
require('../config/config.php');

class Util
{
    static function getConvertedValue($dCoord, $dStartCord, $dMultiplier)
    {
        return (doubleval($dCoord) - doubleval($dStartCord)) * doubleval($dMultiplier);
    }

    static function getXYString($dX, $dY)
    {
        return $dX . ',' . (CANVAS_HEIGHT - $dY);
    }

    static function getData($sQuery)
    {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        // Get Result
        $result = mysqli_query($conn, $sQuery);
        // Fetch Data
        $aData = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Free Result
        mysqli_free_result($result);
        // Close Connection
        mysqli_close($conn);
        return $aData;
    }

    public static function getBusLegendData($sBusId)
    {
        $query = 'SELECT * FROM bus_legend_info WHERE bus_id = "' . $sBusId . '"';
        return self::getData($query);
    }

    static function getBusData($sBusId)
    {
        $query = 'SELECT * FROM bus_list WHERE bus_id = "' . $sBusId . '"';
        return self::getData($query);
    }

    static function getForwardBusStops($aBusStops, $sCurCoord)
    {
        $aData = array();
        $isFound = false;
        // print_r($sCurCoord);
        foreach ($aBusStops AS $sKey => &$aBusStop) {
            if ($sCurCoord == json_encode($aBusStop['Coord'])) {
                $isFound = true;
            }
            if ($isFound) {
                $dNewY = self::getConvertedValue($aBusStop['Coord']['Lng'], START_LTD, Y_MULTIPLIER);
                $dNewX = self::getConvertedValue($aBusStop['Coord']['Ltd'], START_LNG, X_MULTIPLIER);
                $aData[$aBusStop['BusStopId']] = self::getXYString($dNewX, $dNewY);
            }
        }
        return $aData;
    }

    static function getForwardBusRoute($aBusRoute, $sCurCoord)
    {
        $aData = array();
        $isFound = false;
        $oBoundaryMngr = \model\BoundaryManager::getInstance();
        foreach ($aBusRoute AS $sKey => &$aBusPoint) {
            if ($sCurCoord == json_encode($aBusPoint)) {
                $isFound = true;
            }
            if ($isFound) {
                $dNewY = self::getConvertedValue($aBusPoint['Lng'], START_LTD, Y_MULTIPLIER);
                $dNewX = self::getConvertedValue($aBusPoint['Ltd'], START_LNG, X_MULTIPLIER);
                $aData[] = self::getXYString($dNewX, $dNewY);
                $oBoundaryMngr->compareCoords($dNewX, CANVAS_HEIGHT - $dNewY);
            }
        }
        return $aData;
    }

    public static function getBusInfo($sBusId, $sCurCoord)
    {
        $aBus = self::getBusData($sBusId);
        // print_r($aBus);
        // UNSETTING PREVIOUS BUS STOPS
        $aBusStops = json_decode($aBus[0]['stops'], true);
        $aNeedStops = self::getForwardBusStops($aBusStops, $sCurCoord);
        $sBusNumber = $aBus[0]['bus_number'];
        // UNSETTING PREVIOUS ROUTE
        $aBusRoute = json_decode($aBus[0]['route'], true);
        $aNeedRoute = self::getForwardBusRoute($aBusRoute, $sCurCoord);
        // print_r($aBusRoute); exit;

        return array(
            'stops' => $aNeedStops,
            'route' => $aNeedRoute,
            'bus_number' => $sBusNumber
        );
    }

    public static function getPolyline($aRoute, $sColor, $sBusNumber)
    {

        $sPolyline = '<polyline points="';
        foreach ($aRoute AS $sCoord) {
            $sPolyline .= $sCoord . ' ';
        }
        $sPolyline .= '" style="fill:none;stroke:' . $sColor . ';stroke-width:' . STROKE_WIDTH . '" />';
        $sPolyline .= self::getFinalStopCircle($aRoute, $sColor, $sBusNumber);
        return $sPolyline;
    }

    public static function getFinalStopCircle($aRoute, $sColor, $sBusNumber)
    {
        $sFinalStop = explode(",", $aRoute[count($aRoute) - 1]);

        $sCircleRadius = strlen($sBusNumber) <= 2 ? CIRCLE_RADIUS : ((intval(CIRCLE_RADIUS) + 2) . '');
        $sFontStyle = strlen($sBusNumber) <= 2 ? 'style="font-weight:bold;"' : '';
        $dCorrector = strlen($sBusNumber) <= 2 ? 0 : 1;
        $sFinalStopCircle = '<circle cx="' . $sFinalStop[0] . '" cy="' . $sFinalStop[1] . '" r="' . $sCircleRadius . '" fill="' . $sColor . '"/>';
        $sFinalStopCircle .= '<text x="' . $sFinalStop[0] . '" y="' . (doubleval($sFinalStop[1]) + doubleval($sCircleRadius) / 2 - $dCorrector) . '" fill="#ffffff" font-family="'.FONT_FAMILY.'" font-size = "12" text-anchor="middle" alignment-baseline="middle"><tspan ' . $sFontStyle . '>' . $sBusNumber . '</tspan></text>';
        return $sFinalStopCircle;
    }

    public static function getStartStopCircle($sCurCoord)
    {
        $aCurCoord = json_decode($sCurCoord, true);
        $dCenterY = (CANVAS_HEIGHT -self::getConvertedValue($aCurCoord['Lng'], START_LTD, Y_MULTIPLIER));
        $dCenterX = self::getConvertedValue($aCurCoord['Ltd'], START_LNG, X_MULTIPLIER);
        $sStartStopCircle = '<circle cx="' . $dCenterX . '" cy="' . $dCenterY . '" r="19" fill="#e64b3a" stroke="#ffffff" stroke-width="1"/><circle cx="' . $dCenterX . '" cy="' . $dCenterY . '" r="15" fill="#c0392b" stroke="#ffffff" stroke-width="1"/><circle cx="' . $dCenterX . '" cy="' . $dCenterY . '" r="9" fill="#ffffff"/>';
        return $sStartStopCircle;
    }

    public static function getImportantStopCircle($sCurCoord)
    {
        list($dX, $dY) = explode(",", $sCurCoord);
        $sImportantStopCircle = "<circle cx=\"{$dX}\" cy=\"{$dY}\" r=\"6\" fill=\"#ffffff\" stroke=\"#000000\" stroke-width=\"2\"/>";
        return $sImportantStopCircle;
    }

    public static function getSVGMap($sPolylines)
    {
        // Fetching data
        $file = '../src/map_template.txt';
        $sMapTemplate = file_get_contents($file);

        // Processing
        $oBoundaryMngr = \model\BoundaryManager::getInstance();
        $aViewBoxBoundaries = $oBoundaryMngr->getCropBoundaries(CROP_PADDING_OFFSET_X, CROP_PADDING_OFFSET_Y);
        list($iViewBoxStartX, $iViewBoxWidth, $iViewBoxStartY, $iViewBoxHeight) = $aViewBoxBoundaries;

        $sSVGHeader = "<svg x=\"800\" y=\"550\" width=\"1520pt\" height=\"1287pt\" viewBox=\"{$iViewBoxStartX} {$iViewBoxStartY} {$iViewBoxWidth} {$iViewBoxHeight}\" version=\"1.1\">\n";
        $sMapTemplate = $sSVGHeader . $sMapTemplate;
        $sMapTemplate .= $sPolylines;
        $sMapTemplate .= "</svg>\n";

        // Generating
//        $fileOutput = '../src/php_output_test.svg';
//        file_put_contents($fileOutput, $sMapTemplate);
        return $sMapTemplate;
    }
}