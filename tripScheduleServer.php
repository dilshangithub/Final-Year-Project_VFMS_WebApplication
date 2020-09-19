<!--Check login status, session start and include database-->
<?php
session_start();
include_once 'databaseConnector.php';
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver") || (($_SESSION['role'])=="Maintainer") ){
    header('location:accessError.php');
}
?>

<!--Insert data to database-->
<?php
//create variables for hold data
$sDtae ="";
$sTime ="";
$vehicleNo ="";
$driverNo ="";
$start ="";
$start_lang = "";
$start_long = "";
$stop_lang ="";
$stop_long ="";
$destination ="";
$arrTime ="";
$deptTime = "";
$description="";
$price = "";
//check whether the submit button click or not. if the button is clicked, values are assign to variables
if (isset($_POST['submit_scheduleInfo'])){
    $sDtae = $_POST['schedu_date'];
    $sTime = $_POST['schedu_time'];
    $vehicleNo = $_POST['vehicleNo'];
    $driverNo = $_POST['driverNumber'];
    $start = $_POST['from'];
    $start_lang = $_POST['start_lat'];
    $start_long = $_POST['start_lng'];
    $destination = $_POST['to'];
    $stop_lang = $_POST['stop_lat'];
    $stop_long = $_POST['stop_lng'];
    $arrTime = $_POST['arr_time'];
    $deptTime = $_POST['dept_time'];
    $description = $_POST['description'];
    $price = $_POST['cost'];
    //insert query
    $insertScheduleInfo = "INSERT INTO `tripschedule` (`sdate`, `stime`, `vehicleNo`, `driverNo`, `start`,`startLatitude`,`startLongitude`, `destination`,`destinationLatitude`,`destinationLongitude`,`arrTime`,`deptTime`,`description`,`totalPrice`) 
                           VALUES('$sDtae', '$sTime','$vehicleNo','$driverNo', '$start','$start_lang','$start_long', '$destination','$stop_lang','$stop_long','$arrTime','$deptTime','$description','$price')";

    if (mysqli_query($connector,$insertScheduleInfo)){
        $lastId = mysqli_insert_id($connector);
        $_SESSION['lastScheduleId'] = $lastId;
        $_SESSION['tripScheduleInfoInsertSuccessfull'] = 'Record inserted successfully!';
        //the page redirect to fuel management page.
        header('location: tripSchedule.php');

    }
}
?>

<!--Delete trip information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['deleteId'])){
    //assign delete id into variable
    $id = $_GET['deleteId'];
    //delete query
    $deleteQuery = "DELETE from `tripschedule` WHERE `scheduleNo` = '$id'";
    $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
}
?>