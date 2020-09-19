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

<!--insert date to database-->
<?php
//create variables for hold data
$regNo ="";
$type ="";
$modelYear ="";
$make ="";
$color ="";
$perchesDate ="";
$vehicleCapacity ="";
$containerType ="";
$imageData ="";
$chassisNo ="";
$engineNo ="";
$transmission ="";
$tyres ="";
//check whether the submit button click or not. if the button is clicked, values are assign to variables
if (isset($_POST['submitVehicleInfo'])){
    $regNo = $_POST['reg_no'];
    $type = $_POST['v_type'];
    $modelYear = $_POST['model_year'];
    $make = $_POST['make'];
    $color = $_POST['v_color'];
    $perchesDate = $_POST['perch_date'];
    $vehicleCapacity = $_POST['v_capacity'];
    $containerType = $_POST['cont_type'];
    $chassisNo = $_POST['chass_no'];
    $engineNo = $_POST['e_no'];
    $transmission = $_POST['transmission'];
    $tyres = $_POST['noOfTires'];
    $imageData = addslashes (file_get_contents($_FILES['v_image']['tmp_name']));

    //insert query
    $insertVehicleInfo = "INSERT INTO `vehicle` (`regNo`, `type`, `modelYear`, `make`, `color`, `perchesDate`, `capacity`, `containerType`, `image`, `chassisNo`, `engineNo`, `transmission`, `tyres`)VALUES ('$regNo', '$type', '$modelYear', '$make', '$color', '$perchesDate', '$vehicleCapacity', '$containerType','$imageData', '$chassisNo', '$engineNo', '$transmission', '$tyres')";

    $insertVehicleInfoQueryResult = mysqli_query($connector, $insertVehicleInfo) or die (mysqli_error($connector));
    //display insert success message. the message assign to session variable.
    $_SESSION['vehicleInfoInsertSuccessfull'] = 'Record inserted successfully!';
    //the page redirect to fuel management page.
    header('location: vehicleRegistration.php');
}
?>

<!--Update vehicle information-->
<!--Update page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Vehicle Information</title>
    <!--link jquery-->
    <script src="jquery-3.3.1.min.js"></script>
    <!--link proper js-->
    <script src="popper.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!--link css file 1-->
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

<h2 style="margin-top: 100px" class="text-dark text-center">Update Vehicle Information of Vehicle No <?php echo $_GET['updateId'];?></h2>
<!--info update container-->
<div  class="container  w-75 mt-4 rounded-lg pb-2">
    <?php
    //get id of update recode
    $updateId = $_GET['updateId'];
    if(isset($_POST['updateVehicleInfo'])){
        $regNo = $_POST['reg_no'];
        $type = $_POST['v_type'];
        $modelYear = $_POST['model_year'];
        $make = $_POST['make'];
        $color = $_POST['v_color'];
        $perchesDate = $_POST['perch_date'];
        $vehicleCapacity = $_POST['v_capacity'];
        $containerType = $_POST['cont_type'];
        $image = addslashes (file_get_contents($_FILES['v_image']['tmp_name']));
        $chassisNo = $_POST['chass_no'];
        $engineNo = $_POST['e_no'];
        $transmission = $_POST['transmission'];
        $tyres = $_POST['noOfTires'];

        $updateQuery = "UPDATE `vehicle` SET `regNo`='$regNo',`type`='$type',`modelYear`='$modelYear',`make`='$make',`color`='$color',`perchesDate`='$perchesDate', `capacity`='$vehicleCapacity', `containerType`='$containerType', `image`='$image', `chassisNo`='$chassisNo', `engineNo`='$engineNo',`transmission`='$transmission',`tyres`='$tyres' WHERE regNo = '$updateId'";
        $queryResult = mysqli_query($connector, $updateQuery) or die();
        //display update success message. the message assign to session variable.
        $_SESSION['vehicleInfoUpdateSuccessfull'] = 'Record Updated successfully!';
        //the page redirect to fuel management page.
        header('location: vehicleRegistration.php');

    }
    //if user click close window button, then redirect update page into vehicle registration page.
    if (isset($_POST['closeWindow'])){
        header('location: vehicleRegistration.php');
    }

    //get current values from database
    $selectQuery = "SELECT * FROM `vehicle` WHERE `regNo` = '$updateId'";
    $queryResult = mysqli_query($connector, $selectQuery);
    while ($row = mysqli_fetch_row($queryResult)) {
    ?>
    <!--display current values on form-->
    <form method="post" action="" enctype="multipart/form-data">
        <table class="w-100">
            <tr>
                <td><label class="text-dark"><h5><b>Basic Vehicle Information</b></h5></label></td>
                <td>
                    <label class="text-dark"><h5><b>Mechanical Information</b></h5></label>
                </td>

            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="reg_no" id="error-reg-no">Registration Number</label>
                    <input type="text" name="reg_no" id="reg_no" size="11" readonly value="<?php echo $row[0]; ?>"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2" rowspan="2">
                    <label for="v_image" id="error-v-image">Vehicle Image</label>
                    <input type="file" value="" name="v_image" id="v_image"
                           class="form-control-file rounded-lg float-right pl-3 mr-3"><br>
                    <label id="image_errorMsg" class="font-weight-bold" style="font-size: 12px; color: red"></label>
                    <label class="font-weight-bold" style="font-size: 12px; color: red">You must select a photo!</label>
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="v_type" id="error-v-type">Vehicle Type</label>
                    <input type="text" name="v_type" id="v_type" value="<?php echo $row[1]; ?>" size="11"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="model_year" id="error--model-year">Model Year</label>
                    <input type="text" name="model_year" id="model_year" value="<?php echo $row[2]; ?>" size="11"
                           class="rounded-lg float-right ml-2  mr-3">
                </td>
                <td class="tc_position2">
                    <label for="chass_no" id="error-chassis-no">Chassis Number</label>
                    <input type="text" name="chass_no" id="chass_no" value="<?php echo $row[9]; ?>" size="20"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="make" id="error-make">Make</label>
                    <input type="text" name="make" id="make" size="11" value="<?php echo $row[3]; ?>"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="e_no" id="error-engineNo">Engine Number</label>
                    <input type="text" name="e_no" id="e_no" value="<?php echo $row[10]; ?>" size="20"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="v_color" id="error-vehicle-color"> Vehicle Color</label>
                    <input type="text" name="v_color" id="v_color" size="11" value="<?php echo $row[4]; ?>"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="transmission" id="error-transmission">Transmission</label>
                    <select name="transmission" id="transmission" class="rounded-lg float-right ml-2 mr-3">
                        <option>Manual</option>
                        <option>Automatic</option>
                        <option>Automated Manual</option>
                        <option>Continuously Variable</option>
                        <option>Dual-Clutch</option>
                        <option>Tiptronic</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="perch_date" id="error-perches-date">Perches Date</label>
                    <input type="date" name="perch_date" value="<?php echo $row[5]; ?>" id="perch_date"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="noOfTires" id="error-no-Of-tires">Number of Tyres</label>
                    <input type="text" name="noOfTires" id="noOfTires" value="<?php echo $row[12]; ?>" size="20"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="v_capacity" id="error-v-capacity">Vehicle Capacity</label>
                    <input type="text" name="v_capacity" id="v_capacity" value="<?php echo $row[6]; ?>" size="11"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="cont_type" id="error-containerType">Container Type</label>
                    <input type="text" name="cont_type" id="cont_type" value="<?php echo $row[7]; ?>" size="11"
                           class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <!--Update button-->
                    <input type="submit" name="updateVehicleInfo" value="Update Information" class="btn-warning mt-5 rounded-lg w-100 rs_btn_size">
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

<!--Delete insurance information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['deleteId'])){
    //assign delete id into variable
    $id = $_GET['deleteId'];
    //delete query
    $deleteQuery = "DELETE from `vehicle` WHERE `regNo` = '$id'";
    $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
    //display delete success message. the message assign to session variable.
    $_SESSION['vehicleInfoDeleteSuccessfull']= 'Record Deleted successfully!';
    //the page redirect to fuel management page.
    header('location: vehicleRegistration.php');
}
?>