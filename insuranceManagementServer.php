<!--Check login status, session start and include database-->
<?php
//Start session.
session_start();
include_once 'databaseConnector.php';
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver") ||(($_SESSION['role'])=="Scheduler") ){
    header('location:accessError.php');
}
?>

<!--Insert data to database-->
<?php
//create variables for hold data
$date="";
$vehicleNo ="";
$insuranceCertificate ="";
$insu_date ="";
$exp_date ="";
$insuType ="";
$insuranceNo ="";
$insuranceFee ="";
//check whether the submit button click or not. if the button is clicked, values are assign to variables
if (isset($_POST['insurance_Submit'])){
    $date = $_POST['updated_date'];
    $vehicleNo = $_POST['vehicleNo'];
    $insuranceCertificate = $_POST['insu_certificate'];
    $insu_date = $_POST['i_date'];
    $exp_date = $_POST['e_date'];
    $insuType = $_POST['i_type'];
    $insuranceNo = $_POST['i_no'];
    $insuranceFee = $_POST['i_fee'];
    //insert query
    $insertInsuranceInfo = "INSERT INTO `insurancemanagement` (`date`, `vehicleNo`, `insuranceCertificate`, `insuDate`, `iexpireDate`, `insuType`, `insuNo`,`insuFee`) 
                           VALUES('$date', '$vehicleNo','$insuranceCertificate','$insu_date', '$exp_date', '$insuType', '$insuranceNo','$insuranceFee')";
    $insertInsuranceInfoQueryResult = mysqli_query($connector, $insertInsuranceInfo) or die (mysqli_error($connector));
    //display insert success message. the message assign to session variable.
    $_SESSION['insuranceInfoInsertSuccessfull'] = 'Record inserted successfully!';
    //the page redirect to fuel management page.
    header('location: insuranceManagement.php');
}
?>

<!--Update accident information-->
<!--Update page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Insurance Information</title>
    <script src="jquery-3.3.1.min.js"></script>
    <script src="popper.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<!--Upper information bar-->
<div style="background-color: black;" class="w-100 float-right p-2 fixed-top">
    <!--Display Last login time-->
    <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-5 float-right">
        Last Login Time : <?php echo "  ". $_SESSION['lastLogin'];?>
    </div>
    <!--Display user role-->
    <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-4 float-right">
        User Role : <?php echo "  ". $_SESSION['role'];?>
    </div>
    <!--Display user name-->
    <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-4 float-right">
        <?php echo "  ". $_SESSION['firstName'] ." ".$_SESSION['lastName'];?>
    </div>
</div>

<h2 style="margin-top: 100px" class="text-dark text-center">Update Insurance Information of Vehicle No <?php echo $_GET['vehicleNo'];?></h2>

<!--info update container-->
<div class="container rounded-lg shadow-sm mt-5 border w-75 border-dark pb-2">
    <!--Update fuel data-->
    <?php
    //get id of update recode
    $updateId = $_GET['updateId'];
    if (isset($_POST['update_insuranceInfo'])) {
        $date = $_POST['updated_date'];
        $insuranceCertificate = $_POST['insu_certificate'];
        $insu_date = $_POST['i_date'];
        $exp_date = $_POST['e_date'];
        $insuType = $_POST['i_type'];
        $insuranceNo = $_POST['i_no'];
        $insuranceFee = $_POST['i_fee'];
        //update query
        $updateQuery = "UPDATE insurancemanagement 
SET `date`='$date',`insuranceCertificate`='$insuranceCertificate',`insuDate`='$insu_date',`iexpireDate`='$exp_date',`insuType`='$insuType',`insuNo`='$insuranceNo',`insuFee`='$insuranceFee' WHERE id = '$updateId'";
        $queryResult = mysqli_query($connector, $updateQuery);
        //display update success message. the message assign to session variable.
        $_SESSION['insuranceInfoUpdateSuccessfull'] = 'Record Updated successfully!';
        //the page redirect to fuel management page.
        header('location: insuranceManagement.php');
    }
    //if user click close window button, then redirect update page into insurance management page.
    if (isset($_POST['closeWindow'])){
        header('location: insuranceManagement.php');
    }
    //get current values from database
    $query = "SELECT * FROM insurancemanagement WHERE id='$updateId'";
    $updateQueryResult = mysqli_query($connector,$query);
    while ($row= mysqli_fetch_array($updateQueryResult)) {
        ?>
        <!--display current values on form-->
        <form method="post" action="">
            <table class="w-100 mt-5">
                <tr>
                    <td class="tc_position">
                        <label for="updated_date" id="error-date">Date</label>
                        <input type="date" name="updated_date" value="<?php echo $row[1]; ?>" id="updated_date" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="insu_certificate" id="error-insuCertificate">Insurance Certificate</label>
                        <input type="text" name="insu_certificate" value="<?php echo $row[3]; ?>" id="insu_certificate" size="19" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="i_type" id="error-insuranceType">Insurance Type</label>
                        <input type="text" name="i_type" value="<?php echo $row[6]; ?>" id="i_type" size="19" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="i_date" id="error-insuDate">Insurance Date</label>
                        <input type="date" name="i_date" value="<?php echo $row[4]; ?>" id="i_date" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="e_date" id="error-expireDate">Expire Date</label>
                        <input type="date" name="e_date" value="<?php echo $row[5]; ?>" id="e_date" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="i_no" id="error-insuNo">Insurance Number</label>
                        <input type="text" name="i_no" value="<?php echo $row[7]; ?>" id="i_no" size="11" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="i_fee" id="error-insuFee">Insurance Fee</label>
                        <input type="text" name="i_fee" value="<?php echo $row[8]; ?>" id="i_fee" size="11" class="rounded-lg float-right ml-2">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <!--Update button-->
                        <input type="submit" name="update_insuranceInfo" value="Update Information" class="btn-warning mt-5 rounded-lg w-100 rs_btn_size">
                        <!--close window button-->
                        <input type="submit" name="closeWindow" value="Close Update Window" class="btn-danger mt-2 rounded-lg w-100 rs_btn_size">
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }
    ?>
    <br>
</div>
</body>
</html>

<!--Delete insurance information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['deleteId'])){
    //assign delete id into variable
    $id = $_GET['deleteId'];
    //delete query
    $deleteQuery = "DELETE from `insurancemanagement` WHERE `id` = '$id'";
    $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
}
?>