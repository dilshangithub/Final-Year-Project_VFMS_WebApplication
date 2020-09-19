<?php
require_once 'coonect.php';

if($_SERVER['REQUEST_METHOD']=='POST') {
    $password = $_POST['password'];
    $id = $_POST['id'];

    $sql = "UPDATE user SET password ='".base64_encode($password)."' WHERE telephone = '$id'";

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