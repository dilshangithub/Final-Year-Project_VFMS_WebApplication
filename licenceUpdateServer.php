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

<!--license Management-->
<!--Insert license management data to database-->
<?php
//create variables for hold data
$date ="";
$vehicleNo ="";
$licenceNo ="";
$licencedDate ="";
$expiredDate ="";
$licenceFee ="";
$vetCert = "";
$vetCertFee = "";
//check whether the submit button click or not. if the button is clicked, values are assign to variables
if (isset($_POST['submitLicenceInfo'])){
    $date = $_POST['date'];
    $vehicleNo = $_POST['vehicleNo'];
    $licenceNo =$_POST['RL_no'];
    $licencedDate = $_POST['l_date'];
    $expiredDate = $_POST['ex_date'];
    $licenceFee = $_POST['RL_fee'];
    $vetCert = $_POST['VETC'];
    $vetCertFee = $_POST['VETC_fee'];
    //insert query
    $insertLicenceInfo = "INSERT INTO `licencemanagement` (`date`, `vehicleNo`, `licenceNo`, `licencedDate`, `expireDate`, `VETcertificate`,`VETcertificateFee`,`licenceFee`) 
                           VALUES('$date', '$vehicleNo', '$licenceNo', '$licencedDate', '$expiredDate', '$vetCert','$vetCertFee','$licenceFee')";
    $insertLicenceInfoQueryResult = mysqli_query($connector, $insertLicenceInfo) or die (mysqli_error($connector));
    //display insert success message. the message assign to session variable.
    $_SESSION['licenceInfoInsertSuccessfull']= 'Licence Record inserted successfully!';
    //the page redirect to service management page.
    header('location: licenceUpdateManagement.php');
}
?>

<!--Update licence information-->
<!--Update page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Licence Information</title>
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

<h2 style="margin-top: 100px" class="text-dark text-center">Update Licence Information of Vehicle No <?php echo $_GET['vehicleNo'];?></h2>

<!--info update container-->
<div class="container rounded-lg shadow-sm mt-5 border w-75 border-dark pb-2">
    <!--Update fuel data-->
    <?php
    //get id of update recode
    $updateId = $_GET['updateId'];
    if (isset($_POST['update_licenceInfo'])) {
        $date = $_POST['date'];
        $licenceNo = $_POST['licenceNo'];
        $licencedDate = $_POST['l_date'];
        $expiredDate = $_POST['exp_date'];
        $licenceFee = $_POST['licenceFee'];
        $vetCert = $_POST['vet_cert'];
        $vetCertFee = $_POST['vet_certFee'];
        //update query
        $updateQuery = "UPDATE licencemanagement 
SET `date`='$date',`licenceNo`='$licenceNo',`licencedDate`='$licencedDate',`expireDate`='$expiredDate',`VETcertificate`='$vetCert', `VETcertificateFee`='$vetCertFee',`licenceFee`='$licenceFee' WHERE id = '$updateId'";
        $queryResult = mysqli_query($connector, $updateQuery);
        //display update success message. the message assign to session variable.
        $_SESSION['licenceInfoUpdateSuccessfull'] = 'Record Updated successfully!';
        //the page redirect to fuel management page.
        header('location: licenceUpdateManagement.php');
    }
    //if user click close window button, then redirect update page into fuel management page.
    if (isset($_POST['closeWindow'])){
        header('location: licenceUpdateManagement.php');
    }
    //get current values from database
    $query = "SELECT * FROM licencemanagement WHERE id='$updateId'";
    $updateQueryResult = mysqli_query($connector,$query);
    while ($row= mysqli_fetch_array($updateQueryResult)) {
        ?>
        <!--display current values on form-->
        <form method="post" action="">
            <table  class="w-100 mt-5">
                <tr>
                    <td class="tc_position">
                        <label for="get" id="error-date">Date</label>
                        <input type="date" name="date" value="<?php echo $row[1]; ?>" id="date" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="licenceNo"  id="error-time">Licence No</label>
                        <input type="text" name="licenceNo" value="<?php echo $row[3]; ?>" id="licenceNo" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="l_date"  id="error-time">Licenced Date</label>
                        <input type="date" name="l_date" value="<?php echo $row[4]; ?>" id="l_date" size="15" class="rounded-lg float-right mr-3 ml-2">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="exp_date" id="error-noOfLiters">Expire Date</label>
                        <input type="date" name="exp_date" value="<?php echo $row[5]; ?>" id="exp_date" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="licenceFee" id="error-unitPrice">Licence Free</label>
                        <input type="text" name="licenceFee" value="<?php echo $row[8]; ?>" id="licenceFee" size="15" class="rounded-lg float-right ml-2">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="vet_cert" id="error-totalCost">VET Certificate</label>
                        <input type="text" name="vet_cert" value="<?php echo $row[6]; ?>" id="vet_cert" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="vet_certFee" id="error-totalCost">VET Certificate Fee</label>
                        <input type="text" name="vet_certFee" value="<?php echo $row[7]; ?>" id="vet_certFee" size="15" class="rounded-lg float-right ml-2">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <!--Update button-->
                        <input type="submit" name="update_licenceInfo" value="Update Information" class="btn-warning mt-5 rounded-lg w-100 rs_btn_size">
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


<!--Delete fuel information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['deleteId'])){
    //assign delete id into variable
    $id = $_GET['deleteId'];
    //delete query
    $deleteQuery = "DELETE from `licencemanagement` WHERE `id` = '$id'";
    $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
}
?>

