<!--Check login status, session start and include database-->
<?php
//Start session.
session_start();
include_once 'databaseConnector.php';
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page.
if ((($_SESSION['role'])=="Driver") ||(($_SESSION['role'])=="Scheduler") ){
    header('location:accessError.php');
}
?>

<!--Insert data to database-->
<?php
//create variables for hold data
$vehicleNo ="";
$accDate ="";
$accTime ="";
$dPhoneNo ="";
$description ="";
$claimOpt ="";
$expenses ="";
//check whether the submit button click or not. if the button is clicked, values are assign to variables
if (isset($_POST['submitAccidentInfo'])){
    $vehicleNo = $_POST['vehicleNo'];
    $accDate = $_POST['acc_date'];
    $accTime = $_POST['acc_time'];
    $dPhoneNo= $_POST['d_number'];
    $description = $_POST['description'];
    $claimOpt = $_POST['claimOpt'];
    $expenses = $_POST['expenses'];
    //insert query
    $insertAccidentInfo = "INSERT INTO `accidentmanagement` (`vehicleNo`, `driverNo`, `accDate`, `accTime`, `description`, `claimBy`, `expences`) 
                           VALUES('$vehicleNo', '$dPhoneNo','$accDate','$accTime', '$description', '$claimOpt', '$expenses')";
    $insertAccidentInfoQueryResult = mysqli_query($connector, $insertAccidentInfo) or die (mysqli_error($connector));
    //display insert success message. the message assign to session variable.
    $_SESSION['accidentInfoInsertSuccessfull'] = 'Record inserted successfully!';
    //the page redirect to fuel management page.
    header('location: accidentManagement.php');
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
    <title>Update Accident Information</title>
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

<h2 style="margin-top: 100px" class="text-dark text-center">Update Accident Information of Vehicle No <?php echo $_GET['vehicleNo'];?></h2>

<!--info update container-->
<div class="container rounded-lg shadow-sm mt-5 border w-75 border-dark pb-2">
    <!--Update fuel data-->
    <?php
    //get id of update recode
    $updateId = $_GET['updateId'];
    if (isset($_POST['update_accidentInfo'])) {
        $accDate = $_POST['acc_date'];
        $accTime = $_POST['acc_time'];
        $description = $_POST['description'];
        $expenses = $_POST['expenses'];
        //update query
        $updateQuery = "UPDATE accidentmanagement 
SET `accDate`='$accDate',`accTime`='$accTime',`description`='$description',`expences`='$expenses' WHERE id = '$updateId'";
        $queryResult = mysqli_query($connector, $updateQuery);
        //display update success message. the message assign to session variable.
        $_SESSION['accidentInfoUpdateSuccessfull'] = 'Record Updated successfully!';
        //the page redirect to fuel management page.
        header('location: accidentManagement.php');
    }
    //if user click close window button, then redirect update page into fuel management page.
    if (isset($_POST['closeWindow'])){
        header('location: accidentManagement.php');
    }
    //get current values from database
    $query = "SELECT * FROM accidentmanagement WHERE id='$updateId'";
    $updateQueryResult = mysqli_query($connector,$query);
    while ($row= mysqli_fetch_array($updateQueryResult)) {
        ?>
        <!--display current values on form-->
        <form method="post" action="">
            <table class="w-100 mt-5">
                <tr>
                    <td class="tc_position">
                        <label for="acc_date" id="error-date">Accident Date</label>
                        <input type="date" name="acc_date" value="<?php echo $row[3]; ?>" id="acc_date" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="acc_time"  id="error-time">Accident Time</label>
                        <input type="time" name="acc_time" value="<?php echo $row[4]; ?>" id="acc_time" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="expenses" id="error-totalCost">Expenses</label>
                        <input type="text" name="expenses" value="<?php echo $row[7]; ?>" id="expenses" size="15" placeholder="10000.00" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="tc_position">
                        <label for="description" id="error-unitPrice">Description</label>
                        <input type="text" name="description" value="<?php echo $row[5]; ?>" id="description" onkeyup="getTotalFuelCost()" placeholder="120.00" size="55" class="rounded-lg float-right ml-2">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <!--Update button-->
                        <input type="submit" name="update_accidentInfo" value="Update Information" class="btn-warning mt-5 rounded-lg w-100 rs_btn_size">
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

<!--Delete accident information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['deleteId'])){
    //assign delete id into variable
    $id = $_GET['deleteId'];
    //delete query
    $deleteQuery = "DELETE from `accidentmanagement` WHERE `id` = '$id'";
    $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
}
?>