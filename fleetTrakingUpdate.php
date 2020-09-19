<?php
if ($_SERVER['REQUEST_METHOD']=='POST') {
    require_once "databaseConnector.php";
    session_start();
    $result = array();
    $trackingID =  $_POST['vid'];

    $selectQuery = "SELECT vehicleNo,gpscordinate.date,time,latitude,longitude FROM allocatemobile INNER JOIN gpscordinate ON allocatemobile.imei = gpscordinate.imei WHERE vehicleNo = '$trackingID'";
    $queryResult = mysqli_query($connector, $selectQuery);

    while ($row = mysqli_fetch_assoc($queryResult)) {
        $result['lat'] = $row['latitude'];
        $result['lng'] = $row['longitude'];
        $result['vehicleNo'] = $row['vehicleNo'];
        $result['date'] = $row['date'];
        $result['time'] = $row['time'];
    }
    echo json_encode($result);
    http_response_code(200);
}