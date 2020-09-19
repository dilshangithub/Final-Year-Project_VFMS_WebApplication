<?php
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $date = $_POST['date'];
    $time = $_POST['time'];
    $vehicleNo = $_POST['vehicleNo'];
    $fuelType = $_POST['fuelType'];
    $u_price = $_POST['u_price'];
    $noOfLiters = $_POST['noOfLiters'];
    $totalCost = $_POST['totalCost'];

    require_once 'coonect.php';

    $query = "INSERT INTO `fuelmanagement` (`date`, `time`, `vehicleNo`, `fuelType`, `liters`, `unitPrice`, `total`) 
                           VALUES('$date', '$time', '$vehicleNo', '$fuelType', '$noOfLiters', '$u_price', '$totalCost')";

    if (mysqli_query($con,$query)){
        $result["success"] = "1";
        $result["message"] = "success";
        echo json_encode($result);
        mysqli_close($con);
    }
}else{
    $result["success"] = "0";
    $result["message"] = "error";
    echo json_encode($result);
    mysqli_close($con);
}