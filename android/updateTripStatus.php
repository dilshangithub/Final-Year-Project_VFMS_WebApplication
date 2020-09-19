<?php
require_once 'coonect.php';

if($_SERVER['REQUEST_METHOD']=='POST') {
    $scheduleNo = $_POST['scheduleNo'];

    $sql = "UPDATE tripschedule SET status ='completed' WHERE  	scheduleNo  = '$scheduleNo'";

    if (mysqli_query($con, $sql)) {
        $result["success"] = "1";
        $result["message"] = "success";

        echo json_encode($result);
        mysqli_close($con);
    }
}
else {
    $result["success"] = "0";
    $result["message"] = "Error";
    echo json_encode($result);
    mysqli_close($con);
}