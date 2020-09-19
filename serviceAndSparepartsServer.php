<!--Check login status, session start and include database-->
<?php
//Start session.
session_start();
//if username not assign to the session, this page redirect to the login page.
include_once 'databaseConnector.php';
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver") ||(($_SESSION['role'])=="Scheduler") ){
    header('location:accessError.php');
}
?>

<!--Service Management-->
<!--Insert service management data to database-->
<?php
//create variables for hold data
$date ="";
$vehicleNo ="";
$serviceType ="";
$mileage ="";
$description ="";
$serviceCharge ="";
//check whether the submit button click or not. if the button is clicked, values are assign to variables
if (isset($_POST['btnServiceSubmit'])){
    $date = $_POST['date'];
    $vehicleNo = $_POST['vehicleNo'];
    $serviceType = $_POST['service_type'];
    $mileage = $_POST['mileage'];
    $description = $_POST['description'];
    $serviceCharge = $_POST['service_charge'];
    //insert query
    $insertServcieInfo = "INSERT INTO `servicemanagement` (`date`, `vehicleNo`, `serviceType`, `mileage`, `description`, `total`) 
                           VALUES('$date', '$vehicleNo', '$serviceType', '$mileage', '$description', '$serviceCharge')";
    $insertServiceInfoQueryResult = mysqli_query($connector, $insertServcieInfo) or die (mysqli_error($connector));
    //display insert success message. the message assign to session variable.
    $_SESSION['serviceInfoInsertSuccessfull']= 'Service Record inserted successfully!';
    //the page redirect to service management page.
    header('location: serviceNsparePartsManagement.php');
}
?>

    <!--Update service information-->
    <!--Update page-->
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Update Service Information</title>
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
            Last Login Time : <?php echo "  " . $_SESSION['lastLogin']; ?>
        </div>
        <!--Display user role-->
        <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-4 float-right">
            User Role : <?php echo "  " . $_SESSION['role']; ?>
        </div>
        <!--Display user name-->
        <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-4 float-right">
            <?php echo "  " . $_SESSION['firstName'] . " " . $_SESSION['lastName']; ?>
        </div>
    </div>

    <h2 style="margin-top: 100px" class="text-dark text-center">Update Service Information of Vehicle No <?php echo $_GET['vehicleNo']; ?> On <?php echo $_GET['date']; ?> </h2>

    <!--info update container-->
    <div class="container rounded-lg shadow-sm mt-5 border w-75 border-dark pb-2">
        <!--Update fuel data-->
        <?php
        //get id of update recode
        $serviceUpdateId = $_GET['serviceUpdateId'];
        if (isset($_POST['update_serviceInfo'])) {
            $date = $_POST['date'];
            $serviceType = $_POST['serviceType'];
            $mileage = $_POST['mileage'];
            $description = $_POST['description'];
            $total = $_POST['total'];
            //update query
            $updateServiceQuery = "UPDATE servicemanagement 
SET `date`='$date',`serviceType`='$serviceType',`mileage`='$mileage',`description`='$description', `total`='$total' WHERE id = '$serviceUpdateId'";
            $serviceQueryResult = mysqli_query($connector, $updateServiceQuery);
            //display update success message. the message assign to session variable.
            $_SESSION['serviceInfoUpdateSuccessfull'] = 'Record Updated successfully!';
            //the page redirect to service management page.
            header('location: serviceNsparePartsManagement.php');
        }
        //if user click close window button, then redirect update page into service management page.
        if (isset($_POST['closeWindow'])) {
            header('location: serviceNsparePartsManagement.php');
        }
        //get current values from database
        $getServiceQuery = "SELECT * FROM servicemanagement WHERE id='$serviceUpdateId'";
        $s_updateQueryResult = mysqli_query($connector, $getServiceQuery);
        while ($row = mysqli_fetch_array($s_updateQueryResult)) {
            ?>
            <!--display current values on form-->
            <form method="post" action="">
                <table class="w-100 mt-5">
                    <tr>
                        <td class="tc_position">
                            <label for="get" id="error-date">Date</label>
                            <input type="date" name="date" value="<?php echo $row[1]; ?>" id="date" size="15"
                                   class="rounded-lg float-right ml-2">
                        </td>
                        <td class="tc_position">
                            <label for="serviceType" id="error-time">Service Type</label>
                            <input type="text" name="serviceType" value="<?php echo $row[3]; ?>" id="serviceType"
                                   size="15" class="rounded-lg float-right mr-3 ml-2">
                        </td>
                        <td class="tc_position">
                            <label for="mileage" id="error-time">Mileage</label>
                            <input type="text" name="mileage" value="<?php echo $row[4]; ?>" id="mileage" size="15"
                                   class="rounded-lg float-right mr-3 ml-2">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="tc_position">
                            <label for="description" id="error-description">Description</label>
                            <input type="text" name="description" value="<?php echo $row[5]; ?>" id="description"
                                   size="50" placeholder="10000.00" class="rounded-lg float-right ml-2 mr-3">
                        </td>
                        <td class="tc_position">
                            <label for="t_cost" id="error-totalCost">Total Cost</label>
                            <input type="text" name="total" value="<?php echo $row[6]; ?>" id="t_cost" size="15"
                                   placeholder="10000.00" class="rounded-lg float-right ml-2 mr-3">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <!--Update button-->
                            <input type="submit" name="update_serviceInfo" value="Update Information"
                                   class="btn-warning mt-5 rounded-lg w-100 rs_btn_size">
                            <!--close window button-->
                            <input type="submit" name="closeWindow" value="Close Update Window"
                                   class="btn-danger mt-2 rounded-lg w-100 rs_btn_size">
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

<!--Delete service information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['serviceDeleteId'])){
    //assign delete id into variable
    $serviceDeleteId = $_GET['serviceDeleteId'];
    //delete query
    $deleteServiceQuery = "DELETE from `servicemanagement` WHERE `id` = '$serviceDeleteId'";
    $deleteServiceQueryResult = mysqli_query($connector, $deleteServiceQuery)or die();
}
?>

<!--************************************************************************************************************************************-->

<!--Spare Parts Management-->
<!--Insert service management data to database-->
<?php
//create variables for hold data
$date ="";
$vehicleNo ="";
$partName ="";
$qnty ="";
$unitPrice ="";
$total ="";
//check whether the submit button click or not. if the button is clicked, values are assign to variables
if (isset($_POST['btnSparePartsSubmit'])){
    $date = $_POST['date'];
    $vehicleNo = $_POST['vehicleNo'];
    $partName = $_POST['part'];
    $qnty = $_POST['qunty'];
    $unitPrice = $_POST['units'];
    $total = $_POST['spr_total'];
    //insert query
    $insertPartInfo = "INSERT INTO `sparepartsmanagement` (`date`, `vehicleNo`, `partName`, `qunty`, `units`, `total`) 
                           VALUES('$date', '$vehicleNo', '$partName', '$qnty', '$unitPrice', '$total')";
    $insertPartsInfoQueryResult = mysqli_query($connector, $insertPartInfo) or die (mysqli_error($connector));
    //display insert success message. the message assign to session variable.
    $_SESSION['partsInfoInsertSuccessfull']= 'Spare Part Record inserted successfully!';
    //the page redirect to service management page.
    header('location: serviceNsparePartsManagement.php');
}
?>

<!--Delete spare parts information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['partDeleteId'])){
    //assign delete id into variable
    $partDeleteId = $_GET['partDeleteId'];
    //delete query
    $deletePartQuery = "DELETE from `sparepartsmanagement` WHERE `id` = '$partDeleteId'";
    $deletePartQueryResult = mysqli_query($connector, $deletePartQuery)or die();
}
?>