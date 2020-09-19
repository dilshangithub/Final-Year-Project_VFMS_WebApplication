<?php
require_once 'coonect.php';

if($_SERVER['REQUEST_METHOD']=='POST') {
    $imei = $_POST['imei'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $check = "SELECT * FROM `gpscordinate` WHERE imei ='$imei'";
    $listQueryResult = mysqli_query($con, $check);

    //check availability of the record. if not, insert new record
    if (mysqli_num_rows($listQueryResult) == 0) {
        $sql1 = "INSERT INTO `gpscordinate` (`date`, `time`, `imei`, `latitude`, `longitude`) VALUES('$date', '$time', '$imei', '$latitude', '$longitude')";
        $insertInfo = mysqli_query($con, $sql1);
    }
    //check availability of the record. if yes, update record
    elseif (mysqli_num_rows($listQueryResult) == 1) {
        $sql = "UPDATE gpscordinate SET date = '$date',time = '$time', latitude = '$latitude', longitude ='$longitude' WHERE imei  = '$imei'";

        if (mysqli_query($con, $sql)) {
            $locationUpdateStatus["success"] = "1";
            $locationUpdateStatus["message"] = "success";

            echo json_encode($locationUpdateStatus);
            mysqli_close($con);
        } else {
            $locationUpdateStatus["success"] = "0";
            $locationUpdateStatus["message"] = "Error";

            echo json_encode($locationUpdateStatus);
            mysqli_close($con);
        }
    }
}
