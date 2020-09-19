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
if ((($_SESSION['role'])=="Driver") || (($_SESSION['role'])=="Scheduler") || (($_SESSION['role'])=="Maintainer") ) {
    header('location:accessError.php');
}
?>

<!--Update driver information-->
<!--Update page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Driver Information</title>
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

<h2 style="margin-top: 100px" class="text-dark text-center">Update Driver Information of Driver Telephone Number <b> <?php echo $_GET['updateId'];?></b></h2>
<!--info update container-->
<div  class="container  w-75 mt-4 rounded-lg pb-2">
    <?php
    //get id of update recode
    $updateId = $_GET['updateId'];

    if(isset($_POST['updateDriverInfo'])) {
        $d_licenceNo = $_POST['d_licence'];
        $d_licenceExpire = $_POST['licence_expDate'];
        $d_insurance = $_POST['d_insuranceCet'];
        $d_insuExpire = $_POST['insuranceExDate'];
        $d_insuranceNo = $_POST['insuranceNo'];

        $updateQuery1 = "UPDATE `driver` SET `licenseNo`='$d_licenceNo',`lexpireDate`='$d_licenceExpire',`insurance`='$d_insurance',`insuranceNo`='$d_insuranceNo',`iexpireDate`='$d_insuExpire' WHERE driverNo = '$updateId'";
        $queryResult1 = mysqli_query($connector, $updateQuery1) or die();
        //display insert success message. the message assign to session variable.
        $_SESSION['DriverInfoUpdateSuccessfull'] = 'Record inserted successfully!';
        //the page redirect to user management page.
        header('location: userRegistration.php');
    }
    //if user click close window button, then redirect update page into user registration page.
    if (isset($_POST['closeWindow'])){
        header('location: userRegistration.php');
    }

    //get current values from database
    $selectQuery = "SELECT * FROM driver WHERE driverNo = '$updateId'";
    $queryResult = mysqli_query($connector, $selectQuery);
    while ($row = mysqli_fetch_row($queryResult)) {
        ?>
        <!--display current values on form-->
        <form method="post" action="">
            <table class="w-100 mt-5">
                <tr id="hideRow2">
                    <td class="tc_position2">
                        <label for="d_licence" id="error-licenceNo">Driver's Licence No</label>
                        <input type="text" name="d_licence" value="<?php echo $row[1]; ?>" id="d_licence" size="18" placeholder="Eg: B1234567" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                    <td class="tc_position2">
                        <label for="licence_expDate" id="error-licence-expDate">Driver's Licence Expire Date</label>
                        <input type="date" name="licence_expDate" value="<?php echo $row[2]; ?>" id="licence_expDate" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr id="hideRow3">
                    <td class="tc_position2">
                        <label for="d_insuranceCet" id="error-insurance-cet">Driver's Insurance Certificate</label>
                        <input type="text" name="d_insuranceCet" value="<?php echo $row[3]; ?>" id="d_insuranceCet" size="18" placeholder="Eg: Sri Lanka Insurance" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                    <td class="tc_position2">
                        <label for="insuranceExDate" id="error-insuranceEXdate">Insurance Expire Date</label>
                        <input type="date" name="insuranceExDate" value="<?php echo $row[5]; ?>" id="insuranceExDate" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr id="hideRow4">
                    <td class="tc_position2">
                        <label for="insuranceNo" id="error-insuranceNo">Insurance No</label>
                        <input type="text" name="insuranceNo" value="<?php echo $row[4]; ?>" id="insuranceNo" size="18" placeholder="321455645" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <!--Update button-->
                        <input type="submit" name="updateDriverInfo" value="Update Information" class="btn-warning mt-5 rounded-lg w-100 rs_btn_size">
                        <!--close window button-->
                        <input type="submit" name="closeWindow" value="Close Update Window" class="btn-danger mt-2 rounded-lg w-100 rs_btn_size">
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }
    ?>
</div>
</body>
</html>
