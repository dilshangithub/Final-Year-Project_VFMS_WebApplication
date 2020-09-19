<?php
if ($_SERVER['REQUEST_METHOD']=='POST') {

    $vehicleNo = $_POST['vehicleNo'];

    require_once 'coonect.php';
    session_start();

    $query = "SELECT * FROM `tripschedule` WHERE status ='notCompleted' AND vehicleNo ='$vehicleNo' LIMIT 1";
    $response = mysqli_query($con,$query);

    $result = array();
    $result['tripData'] = array();

    if (mysqli_num_rows($response)===1) {
        $row = mysqli_fetch_assoc($response);

        $index['from'] = $row['start'];
        $index['startLatitude'] = $row['startLatitude'];
        $index['startLongitude'] = $row['startLongitude'];
        $index['scheduleNo'] = $row['scheduleNo'];
        $index['to'] = $row['destination'];
        $index['destinationLatitude'] = $row['destinationLatitude'];
        $index['destinationLongitude'] = $row['destinationLongitude'];
        $index['arrTime'] = $row['arrTime'];
        $index['deptTime'] = $row['deptTime'];
        $index['description'] = $row['description'];

        array_push($result['tripData'], $index);

        $result['success'] = "1";
        echo json_encode($result);
        mysqli_close($con);
    }
    else{
        $result['success'] = "0";
        echo json_encode($result);
        mysqli_close($con);
    }
}
