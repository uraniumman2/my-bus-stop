<?php
require('config/db.php');
function getBusStopList() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $query = 'SELECT * FROM bus_stops_list WHERE caption != "" ORDER BY trim(caption) ASC';
    
    // Get Result
    $result = mysqli_query($conn, $query);
    
    // Fetch Data
    $aStops = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Free Result
    mysqli_free_result($result);

    // Close Connection
    mysqli_close($conn);

    return $aStops;
}
