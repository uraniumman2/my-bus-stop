<?php
    // Create Connection
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if(mysqli_connect_errno()){
        // Connection Failed
        echo 'Failed to connect to MySQL '. mysqli_connect_errno();
    } else {
        // if()
        // updateDateBase();
    }
    function updateDateBase() {
        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, 'http://developer.smartastanaapp.com/OpenApi/token');
            curl_setopt($curl, CURLOPT_POST, 1);
            $headerPOST[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerPOST);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "password=". SMART_USER_ID ."&username=". SMART_USER_NAME ."&grant_type=password");
            $aResponsePOST = json_decode(curl_exec ($curl), true);
            curl_close($curl);
            $accessToken = isset($aResponsePOST['access_token']) ? $aResponsePOST['access_token'] : '';
            
            // print_r($accessToken);
            $aResponseGET = array();
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'http://developer.smartastanaapp.com/OpenApi/api/CityBuses/GetBusData');
            $headerGET[] = 'Authorization:Bearer ' . $accessToken;
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerGET);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            // $aResponseGET = json_decode($response, true);
            // print_r('result', $out);
            curl_close($curl);
            echo $response . "<br>";

            // ОБНОВЛЕНИЕ ДАННЫХ
            // $aStops = array();
            // $aBuses = array();
            // foreach($aResponseGET['buses'] AS $aBus) {
            //     foreach($aBus['Routes'] AS $aRoute) {
            //         // Заполняем все поля остановки
            //         foreach($aRoute['Stops'] AS $aStop) {
            //             if(isset($aStops[$aStop['BusStopId']])) {
            //                 $aStops[$aStop['BusStopId']]['buses'][] = $aRoute['ObjectId'];
            //             } else {
            //                 $aStops[$aStop['BusStopId']] = array(
            //                     'caption' => $aStop['Caption'],
            //                     'buses'   => array($aRoute['ObjectId']),
            //                     'coord'   => $aStop['Coord']
            //                 );
            //             }
            //         }

            //         // Заполняем все автобусы
            //         $aBuses[$aRoute['ObjectId']] = array(
            //             'caption' => $aRoute['Caption'],
            //             'bus_number' => $aRoute['BusNumber'],
            //             'route' => $aRoute['Route'],
            //             'stops' => $aRoute['Stops']
            //         );
            //     }
            // }
            // // var_dump($aStops);

            // // Удаляем предыдующие значения
            // mysqli_query($conn, "DELETE FROM bus_stops_list");
            // foreach($aStops AS $iKey => $aValue) {
            //     // Заполняем новыми данными
            //     $query = "INSERT INTO bus_stops_list(bus_stop_id, caption, coord, buses) VALUES('$iKey', '" .$aValue["caption"]. "', '". json_encode($aValue["coord"]) ."', '". json_encode($aValue["buses"]) ."')";
            //     mysqli_query($conn, $query);
            // }
            // // var_dump($aBuses);

            // // Удаляем предыдующие значения
            // mysqli_query($conn, "DELETE FROM bus_list");
            // foreach($aBuses AS $sKey => $aValue) {
            //     // Заполняем новыми данными
            //     $query = "INSERT INTO bus_list(bus_id, caption, bus_number, route, stops) VALUES('$sKey', '" .$aValue["caption"]. "', '". $aValue["bus_number"] ."', '". json_encode($aValue["route"]) ."', '". json_encode($aValue["stops"]) ."')";
            //     mysqli_query($conn, $query);
            // }
        }
        //5bf09b81a6371994f02dfc9c618f6464 // AUTH
    }