<?php
    require('../config/config.php');

    function getBusInfo($sBusId, $sCurCoord) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = 'SELECT * FROM bus_list WHERE bus_id = "{$sBusId}"';
        // Get Result
        $result = mysqli_query($conn, $query);
    
        // Fetch Data
        $aBus = mysqli_fetch_all($result, MYSQLI_ASSOC);
        // Free Result
        mysqli_free_result($result);

        // UNSETTING PREVIOUS BUS STOPS
        $aBusStops = json_decode($aBus[0]['stops'], true);
        $aNeedStops = array();
        $isFound = false;
        // print_r($sCurCoord);
        foreach($aBusStops AS $sKey => &$aBusStop) {
            if($sCurCoord == json_encode($aBusStop['Coord'])) {
                $isFound = true;
            }
            if($isFound) {
                $aBusStop['Coord']['lat'] = $aBusStop['Coord']['Lng'];
                $aBusStop['Coord']['lng'] = $aBusStop['Coord']['Ltd'];
                unset($aBusStop['Coord']['Ltd'], $aBusStop['Coord']['Lng']);
                $aNeedStops[] = $aBusStop;
            }
        }
        $aBus[0]['stops'] = json_encode($aNeedStops);

        // UNSETTING PREVIOUS ROUTE
        $aBusRoute = json_decode($aBus[0]['route'], true);
        $aNeedRoute = array();
        $isFound = false;
        foreach($aBusRoute AS $sKey => &$aBusPoint) {
            if($sCurCoord == json_encode($aBusPoint)) {
                $isFound = true;
            }
            if($isFound) {
                $aBusPoint['lat'] = $aBusPoint['Lng'];
                $aBusPoint['lng'] = $aBusPoint['Ltd'];
                unset($aBusPoint['Ltd'], $aBusPoint['Lng']);
                $aNeedRoute[] = $aBusPoint;
            }
        }
        // print_r($aBusRoute); exit;
        $aBus[0]['route'] = json_encode($aNeedRoute);
    
        // Close Connection
        mysqli_close($conn);
    
        return $aBus;
    }

    $request_body = json_decode(file_get_contents('php://input'), true);
    // print_r($request_body);
    $aBuses = json_decode($request_body['buses'], true);
    $sCurCoord = $request_body['current_coord'];
    // print_r($aBuses);
    $aFetchedData = array();
    foreach($aBuses AS $aBus) {
        $aFetchedData = array_merge($aFetchedData, getBusInfo($aBus, $sCurCoord));
    }

    echo json_encode($aFetchedData);