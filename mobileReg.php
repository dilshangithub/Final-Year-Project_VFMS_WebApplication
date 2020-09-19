<?php
//Start session.
session_start();
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver") || (($_SESSION['role'])=="Scheduler") || (($_SESSION['role'])=="Maintainer") ){
    header('location:accessError.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Allocate Mobile Phone</title>
    <!--link jquery-->
    <script src="jquery-3.3.1.min.js"></script>
    <!--link proper js-->
    <script src="popper.js"></script>
    <!--link sweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!--link bootstrap style sheet file-->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!--link css file 1-->
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>
        //validate mobile allocation inputs
        function mobilePhoneAllocateForm() {
            var date = document.forms["mobilePhoneAllocate-form"]["date"].value;
            var brand = document.forms["mobilePhoneAllocate-form"]["Brand"].value;
            var model = document.forms["mobilePhoneAllocate-form"]["Model"].value;
            var imei = document.forms["mobilePhoneAllocate-form"]["IMEI"].value;
            var vehicleNo = document.forms["mobilePhoneAllocate-form"]["v_no"].value;
            var error_flag = false;

            //vehicle No
            if(vehicleNo=="Vehicle_No"){
                document.getElementById('error-vehicleNo').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-vehicleNo').style.color = "";
            }
            //date
            if(date==''){
                document.getElementById('error-date').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-date').style.color = "";
            }
            //brand
            if(brand==''){
                document.getElementById('error-Brand').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-Brand').style.color = "";
            }
            //model
            if(model==''){
                document.getElementById('error-Model').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-Model').style.color = "";
            }
            //imei
            if(imei==''){
                document.getElementById('error-Imei').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-Imei').style.color = "";
            }
            if (error_flag){
                return false;
            }else{
                return true;
            }
        }
        function closeWindow() {
            if (confirm("Close window?")){
                close();
            }
        }
    </script>
</head>
<body>
<div style="background-color: black;" class="w-100 float-right p-2 fixed-top">
    <button class="btn btn-danger float-right mr-5" onclick="closeWindow()">Close Window</button>
</div>

    <div style="margin-top: 100px;" class="container border border-dark pb-3 rounded-lg">
    <div class="mt-2">
        <form method="post" action="mobileRegServer.php"  name="mobilePhoneAllocate-form" onsubmit="return mobilePhoneAllocateForm()">
            <label class="text-dark"><b>Allocate Mobile Phone to vehicle</b></label>

            <!--recode insert successfully message display-->
            <?php if (isset($_SESSION['mobileInfoInsertSuccessfull'])): ?>
                <script>
                    Swal.fire(
                        'Inserted!',
                        'Record inserted successfully!',
                        'success'
                    )
                </script>
                <?php
                unset($_SESSION['mobileInfoInsertSuccessfull']);
                ?>
            <?php endif ?>

            <?php if (isset($_SESSION['vehicleExist'])): ?>
                <script>
                    Swal.fire(
                        'The vehicle already exist!',
                        'You cannot allocate more than one mobile phone to same vehicle..',
                        'error'
                    )
                </script>
                <?php
                unset($_SESSION['vehicleExist']);
                ?>
            <?php endif ?>

            <table class="w-100">
                <tr>
                    <td class="tc_position">
                        <label for="date" id="error-date">Date</label>
                        <input type="date" name="date" id="date" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="Brand"  id="error-Brand">Brand</label>
                        <input type="text" name="Brand" id="Brand" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="Model" id="error-Model">Model</label>
                        <input type="text" name="Model" id="Model" class="rounded-lg float-right mr-3 ml-2">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="IMEI" id="error-Imei">IMEI</label>
                        <input type="text" name="IMEI" id="IMEI" class="rounded-lg float-right ml-2">
                    </td>
                    <td class="tc_position">
                        <label for="v_no" id="error-vehicleNo">Vehicle Number</label>
                        <select name="vehicleNo" id="v_no" class="rounded-lg ml-2 mr-3">
                            <?php
                            require_once "databaseConnector.php";
                            $selectQuery = "SELECT `regNo` FROM `vehicle`";
                            $queryResult = mysqli_query($connector, $selectQuery);
                            echo '<option value="Vehicle_No">Vehicle No</option>';
                            while ($row = mysqli_fetch_assoc($queryResult)){
                                echo '<option value="'. $row['regNo'] . '">'
                                    . $row['regNo'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <div class="mt-2">
                            <input type="submit" name="submitMobileAllo" value="Submit" class="btn-info rounded-lg float-right rs_btn_size ml-4 mr-3">
                            <input type="reset" name="reset" value="Reset" class="btn-success rounded-lg float-right rs_btn_size">
                        </div>
                    </td>
                </tr>
                <tr>
                </tr>
            </table>
        </form>
</div>
</div>

<div class="container rounded-lg pt-2 mb-4 mt-5 border border-dark">
    <!--search-->
    <form method="post" action="" class="pb-4">
        <label class="text-dark pb-2"><b>Search Allocation</b></label><br>

        <table>
            <tr>
                <td class="tc_position2">
                    <select name="searchVehicleNo" id="v_no" class="rounded-lg">
                        <?php
                        $searchQuery = "SELECT `vehicleNo` FROM `allocatemobile`";
                        $queryResult = mysqli_query($connector, $searchQuery);
                        echo '<option>Vehicle No</option>';
                        while ($row = mysqli_fetch_assoc($queryResult)){
                            echo '<option value="'. $row['vehicleNo'] . '">'
                                . $row['vehicleNo'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
                <td class="tc_position2">
                    <input type="submit" name="btn_search" value="Search" class="rounded-lg btn-info ml-2 rs_btn_size">
                </td>
            </tr>
        </table>
    </form>
    <!--history table-->
    <?php
    //search vehicle
    if (isset($_POST['btn_search'])){
        $searchVal = $_POST['searchVehicleNo'];
        $query = "SELECT * FROM `allocatemobile` WHERE vehicleNo='$searchVal'";
        $listQueryResult = mysqli_query($connector,$query);

        if (mysqli_num_rows($listQueryResult)>0){
            //showing data inside a table
            echo '<table class="table border shadow-sm">';
            echo '<tr class="thead-dark">';
            //display table columns
            echo '<th class="historyTableTxt text-center">VehicleNo</th>';
            echo '<th class="historyTableTxt text-center">Date</th>';
            echo '<th class="historyTableTxt text-center">Brand</th>';
            echo '<th class="historyTableTxt text-center">Model</th>';
            echo '<th class="historyTableTxt text-center">IMEI Number</th>';
            echo '<th class="historyTableTxt text-center"></th>';
            echo '</tr>';
            // output data of each row
            //Creates a loop to loop through results
            while ($row= mysqli_fetch_assoc($listQueryResult)){
                echo "<tr id='$row[imei]'>
                            <td class='text historyTableTxt text-center'>" . $row['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['date'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['brand'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['model'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['imei'] . "</td>
                            <td class='text historyTableTxt text-center'>
                            <button name='delete' value='D' class='btn btn-danger float-right ml-2 historyTableTxt deleteRecord'>D</button></a>
                    </tr>";
            }
            echo '</table>';
        }
        else {
            echo '<div class="ml-3 mb-4"><lable class="alert alert-danger"><b>Warning!</b> Cannot find any Records!</lable></div>';
        }
    }
    ?>
</div>
</body>
</html>

<!--Script for delete record-->
<script type="text/javascript">
    //javascript function call from class name
    $(".deleteRecord").click(function(){
            //assign delete record id
            var id = $(this).parents("tr").attr("id");
            //generate yes/no dialog box using SweetAlert
            Swal.fire({
                title: 'Are you sure to delete this record?',
                text: "Once you delete it, You won't be able to revert!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    //ajax for delete record.
                    $.ajax({
                        url: 'mobileRegServer.php',
                        type: 'GET',
                        data: {deleteId: id},
                        error: function() {
                            alert('Something is wrong');
                        },
                        success: function(data) {
                            //remove record from displayed table on UI
                            $("#"+id).remove();
                        }
                    });
                    //display success message using SweetAlert
                    Swal.fire(
                        'Record Deleted!',
                        'Record has been deleted.',
                        'success'
                    )
                }
            })
        }
    );
</script>

