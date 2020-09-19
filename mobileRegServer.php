<?php
//Start session.
session_start();
require_once "databaseConnector.php";
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver") || (($_SESSION['role'])=="Scheduler") || (($_SESSION['role'])=="Maintainer") ){
    header('location:accessError.php');
}
?>

<!--Insert data to database-->
<?php
if (isset($_POST['submitMobileAllo'])) {
    $setVehicleNo = $_POST['vehicleNo'];
    $selectQuery = "SELECT * FROM `allocatemobile` WHERE vehicleNo ='$setVehicleNo' ";
    $queryResult = mysqli_query($connector, $selectQuery);
    $row= mysqli_fetch_assoc($queryResult);
    $getVehicleNo = $row['vehicleNo'];

    //check the vehicle for allocate phone
    if ($row['vehicleNo']){
        $_SESSION['vehicleExist']= 'The vehicle already exist! You cannot allocate more than one mobile phone to same vehicle..';
        header('location: mobileReg.php');
    }else{
        $date = $_POST['date'];
        $brand = $_POST['Brand'];
        $model = $_POST['Model'];
        $imei = $_POST['IMEI'];
        $vehicleNo = $_POST['vehicleNo'];

        $insertMobileInfo = "INSERT INTO `allocatemobile` (`date`, `brand`, `model`, `imei`, `vehicleNo`) 
                           VALUES('$date', '$brand', '$model', '$imei', '$vehicleNo')";
        $insertMobileInfoQueryResult = mysqli_query($connector, $insertMobileInfo) or die (mysqli_error($connector));

        $_SESSION['mobileInfoInsertSuccessfull']= 'Record inserted successfully!';
        header('location: mobileReg.php');
    }
}
?>
<!--Delete mobile phone information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['deleteId'])){
    //assign delete id into variable
    $id = $_GET['deleteId'];
    //delete query
    $deleteQuery = "DELETE from `allocatemobile` WHERE `imei` = '$id'";
    $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
}
?>