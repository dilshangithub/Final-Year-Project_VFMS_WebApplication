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
    $date ="";
    $time ="";
    $vehicleNo ="";
    $noOfLiters ="";
    $unitPrice ="";
    $total ="";
    $fType ="";
//check whether the submit button click or not. if the button is clicked, values are assign to variables
    if (isset($_POST['submit_fuelInfo'])){
        $date = $_POST['date'];
        $time = $_POST['time'];
        $vehicleNo = $_POST['vehicleNo'];
        $noOfLiters = $_POST['noOfLiters'];
        $unitPrice = $_POST['unit_price'];
        $total = $_POST['total_cost'];
        $fType = $_POST['fuelType'];
    //insert query
        $insertFuelInfo = "INSERT INTO `fuelmanagement` (`date`, `time`, `vehicleNo`, `fuelType`, `liters`, `unitPrice`, `total`) 
                           VALUES('$date', '$time', '$vehicleNo', '$fType', '$noOfLiters', '$unitPrice', '$total')";
        $insertFuelInfoQueryResult = mysqli_query($connector, $insertFuelInfo) or die (mysqli_error($connector));
    //display insert success message. the message assign to session variable.
        $_SESSION['fuelInfoInsertSuccessfull']= 'Record inserted successfully!';
    //the page redirect to fuel management page.
        header('location: fuelManagement.php');
    }
?>

<!--Update fuel information-->
<!--Update page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Fuel Information</title>
    <script src="jquery-3.3.1.min.js"></script>
    <script src="popper.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>
        //Auto calculate total fuel cost
        function getTotalFuelCost(){

            var liters = document.getElementById('liters').value;
            var unitPrice = document.getElementById('u_price').value;
        //equation for calculate total
            var totalCost = parseFloat(liters) * parseFloat(unitPrice);

            var txt_TotalCost = document.getElementById('t_cost');
         //limit the result to two decimal points.
            txt_TotalCost.value=totalCost.toFixed( 2 );
        }
    </script>
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

<h2 style="margin-top: 100px" class="text-dark text-center">Update Fuel Information of Vehicle No <?php echo $_GET['vehicleNo'];?> On  <?php echo $_GET['date'];?> </h2>

<!--info update container-->
<div class="container rounded-lg shadow-sm mt-5 border w-75 border-dark pb-2">
    <!--Update fuel data-->
    <?php
    //get id of update recode
    $updateId = $_GET['updateId'];
    if (isset($_POST['update_fuelInfo'])) {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $fType = $_POST['fuelType'];
        $noOfLiters = $_POST['noOfLiters'];
        $unitPrice = $_POST['unit_price'];
        $total = $_POST['total_cost'];
        //update query
        $updateQuery = "UPDATE fuelmanagement 
SET `date`='$date',`time`='$time',`fuelType`='$fType',`liters`='$noOfLiters',`unitPrice`='$unitPrice', `total`='$total' WHERE id = '$updateId'";
        $queryResult = mysqli_query($connector, $updateQuery);
        //display update success message. the message assign to session variable.
        $_SESSION['fuelInfoUpdateSuccessfull'] = 'Record Updated successfully!';
        //the page redirect to fuel management page.
        header('location: fuelManagement.php');
    }
    //if user click close window button, then redirect update page into fuel management page.
    if (isset($_POST['closeWindow'])){
        header('location: fuelManagement.php');
    }
    //get current values from database
    $query = "SELECT * FROM fuelmanagement WHERE id='$updateId'";
    $updateQueryResult = mysqli_query($connector,$query);
    while ($row= mysqli_fetch_array($updateQueryResult)) {
        ?>
        <!--display current values on form-->
        <form method="post" action="">
            <table class="w-100 mt-5">
                <tr>
                    <td class="tc_position">
                        <label for="get" id="error-date">Date</label>
                        <input type="date" name="date" value="<?php echo $row[1]; ?>" id="date" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="time"  id="error-time">Time</label>
                        <input type="time" name="time" value="<?php echo $row[2]; ?>" id="time" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="fuelType"  id="error-time">Fuel Type</label>
                        <input type="text" name="fuelType" value="<?php echo $row[4]; ?>" id="fuelType" size="15" class="rounded-lg float-right mr-3 ml-2">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="liters" id="error-noOfLiters">No of Liters</label>
                        <input type="text" name="noOfLiters" value="<?php echo $row[5]; ?>" id="liters" onkeyup="getTotalFuelCost()" placeholder="Number of Liters" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="u_price" id="error-unitPrice">Unit Price</label>
                        <input type="text" name="unit_price" value="<?php echo $row[6]; ?>" id="u_price" onkeyup="getTotalFuelCost()" placeholder="120.00" size="15" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="t_cost" id="error-totalCost">Total Cost</label>
                        <input type="text" name="total_cost" value="<?php echo $row[7]; ?>" id="t_cost" readonly size="15" placeholder="10000.00" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <!--Update button-->
                        <input type="submit" name="update_fuelInfo" value="Update Information" class="btn-warning mt-5 rounded-lg w-100 rs_btn_size">
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
        $deleteQuery = "DELETE from `fuelmanagement` WHERE `id` = '$id'";
        $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
    }
?>

