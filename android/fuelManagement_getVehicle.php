<?php
if ($_SERVER['REQUEST_METHOD']=='POST') {

    $imei = $_POST['imei'];

    require_once 'coonect.php';
    session_start();

    $query = "SELECT * FROM allocatemobile WHERE imei ='$imei'";
    $response = mysqli_query($con,$query);

    $result = array();
    $result['vehicleData'] = array();

    if (mysqli_num_rows($response)===1) {
        $row = mysqli_fetch_assoc($response);

        $index['vehicleNo'] = $row['vehicleNo'];
        $index['getImei'] = $row['imei'];
        array_push($result['vehicleData'], $index);

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
