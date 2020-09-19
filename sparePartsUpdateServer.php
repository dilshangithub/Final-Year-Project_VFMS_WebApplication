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

<!--Update service information-->
<!--Update page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Spare Part Information</title>
    <script src="jquery-3.3.1.min.js"></script>
    <script src="popper.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>

        //Auto calculate total parts cost
        function getTotalPartsCost(){

            var qunty = document.getElementById('sparePartsQunty').value;
            var unitPrice = document.getElementById('sparePartsUnits').value;

            var totalCost = parseFloat(qunty) * parseFloat(unitPrice);

            var txt_TotalCost = document.getElementById('sparePartsTotal');
            txt_TotalCost.value=totalCost.toFixed( 2 );
        }
    </script>
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

<h2 style="margin-top: 100px" class="text-dark text-center">Update Spare Parts Information of Vehicle
    No <?php echo $_GET['vehicleNo']; ?> On <?php echo $_GET['date']; ?> </h2>

<!--info update container-->
<div class="container rounded-lg shadow-sm mt-5 border w-75 border-dark pb-2">
    <!--Update fuel data-->
    <?php
    //get id of update recode
    $partUpdateId = $_GET['partUpdateId'];
    if (isset($_POST['update_partInfo'])) {
        $date = $_POST['date'];
        $partName = $_POST['part'];
        $qunty = $_POST['qunty'];
        $unitPrice = $_POST['units'];
        $total = $_POST['spr_total'];
        //update query
        $updatePartsQuery = "UPDATE sparepartsmanagement 
SET `date`='$date',`partName`='$partName',`qunty`='$qunty',`units`='$unitPrice', `total`='$total' WHERE id = '$partUpdateId'";
        $partsQueryResult = mysqli_query($connector, $updatePartsQuery);
        //display update success message. the message assign to session variable.
        $_SESSION['partsInfoUpdateSuccessfull'] = 'Record Updated successfully!';
        //the page redirect to service management page.
        header('location: serviceNsparePartsManagement.php');
    }
    //if user click close window button, then redirect update page into service management page.
    if (isset($_POST['closeWindow'])) {
        header('location: serviceNsparePartsManagement.php');
    }
    //get current values from database
    $getPartsQuery = "SELECT * FROM sparepartsmanagement WHERE id='$partUpdateId'";
    $s_updateQueryResult = mysqli_query($connector, $getPartsQuery);
    while ($row = mysqli_fetch_array($s_updateQueryResult)) {
        ?>
        <!--display current values on form-->
        <form method="post" action="">
                        <div class="mt-4">
                        <label for="get" id="error-date">Date</label>
                        <input type="date" name="date" value="<?php echo $row[1]; ?>" id="date" size="15" class="rounded-lg ml-2">
                        </div>
            <table class="table mt-3" id="tbl_spareParts">
                <thead>
                <th class="text-center">Spare Part</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Unit Price</th>
                <th class="text-center">Total</th>
                </thead>
                <tr>
                    <td><input type="text" name="part" value="<?php echo $row[3]; ?>" id="s_part" class="rounded-sm sprPartsTableTxt" ></td>
                    <td><input type="number" name="qunty" value="<?php echo $row[4]; ?>" id="sparePartsQunty" onkeyup="getTotalPartsCost()" class="rounded-sm sprPartsTableTxt numeric_value" ></td>
                    <td><input type="text" name="units" value="<?php echo $row[5]; ?>" id="sparePartsUnits" onkeyup="getTotalPartsCost()" class="rounded-sm sprPartsTableTxt numeric_value" ></td>
                    <td><input type="text" name="spr_total" value="<?php echo $row[6]; ?>" id="sparePartsTotal" readonly class="rounded-sm sprPartsTableTxt" ></td>
                </tr>
            </table>
            <table class="w-100 mt-5">
                <tr>
                    <td colspan="3">
                        <!--Update button-->
                        <input type="submit" name="update_partInfo" value="Update Information"
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