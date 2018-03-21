<?php
    namespace model;
    require('../config/config.php');
    class Util {
        static function getConvertedValue($dCoord, $dStartCord, $dMultiplier) {
            return (doubleval($dCoord) - doubleval($dStartCord)) * doubleval($dMultiplier);
        }

        static function getXYString($dX, $dY) {
            return $dX . ','. ( CANVAS_HEIGHT - $dY );
        }
        static function getData($sQuery) {
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

        public static function getBusLegendData($sBusId) {
            $query = 'SELECT * FROM bus_legend_info WHERE bus_id = "' .$sBusId. '"';
            return self::getData($query);
        }

        static function getBusData($sBusId) {
            $query = 'SELECT * FROM bus_list WHERE bus_id = "' .$sBusId. '"';
            return self::getData($query);
        }

        static function getForwardBusStops($aBusStops, $sCurCoord) {
            $aData = array();
            $isFound = false;
            // print_r($sCurCoord);
            foreach($aBusStops AS $sKey => &$aBusStop) {
                if($sCurCoord == json_encode($aBusStop['Coord'])) {
                    $isFound = true;
                }
                if($isFound) {
                    $dNewY   = self::getConvertedValue($aBusStop['Coord']['Lng'], START_LTD, Y_MULTIPLIER);
                    $dNewX   = self::getConvertedValue($aBusStop['Coord']['Ltd'], START_LNG, X_MULTIPLIER);
                    $aData[] = self::getXYString($dNewX, $dNewY);
                }
            }
            return $aData;
        }

        static function getForwardBusRoute($aBusRoute, $sCurCoord) {
            $aData = array();
            $isFound = false;
            foreach($aBusRoute AS $sKey => &$aBusPoint) {
                if($sCurCoord == json_encode($aBusPoint)) {
                    $isFound = true;
                }
                if($isFound) {
                    $dNewY   = self::getConvertedValue($aBusPoint['Lng'], START_LTD, Y_MULTIPLIER);
                    $dNewX   = self::getConvertedValue($aBusPoint['Ltd'], START_LNG, X_MULTIPLIER);
                    $aData[] = self::getXYString($dNewX, $dNewY);
                }
            }
            return $aData;
        }

        public static function getBusInfo($sBusId, $sCurCoord) {
            $aBus = self::getBusData($sBusId);
            // print_r($aBus);
            // UNSETTING PREVIOUS BUS STOPS
            $aBusStops = json_decode($aBus[0]['stops'], true);
            $aNeedStops = self::getForwardBusStops($aBusStops, $sCurCoord);

            // UNSETTING PREVIOUS ROUTE
            $aBusRoute = json_decode($aBus[0]['route'], true);
            $aNeedRoute = self::getForwardBusRoute($aBusRoute, $sCurCoord);
            // print_r($aBusRoute); exit;

            return array(
                'stops' => $aNeedStops,
                'route' => $aNeedRoute
            );
        }

        public static function getPolyline($aRoute, $sColor) {
            $sPolyline = '<polyline points="';
            foreach($aRoute AS $sCoord) {
                $sPolyline .= $sCoord . ' ';
            }
            $sPolyline .= '" style="fill:none;stroke:' . $sColor . ';stroke-width:'. STROKE_WIDTH .'" />';
            return $sPolyline;
        }

        public static function getSVGTemplate() {
            $file = '../src/map_template.txt';
            if(file_exists($file)) {
                $handle = fopen($file, 'r');
                $template = fread($handle, filesize($file));
                fclose($handle);
                return $template;
            }
            return false;
        }
    }