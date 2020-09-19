<?php
//Start session.
session_start();
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver") ||(($_SESSION['role'])=="Scheduler") ){
    header('location:accessError.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Service & Spare Parts Management</title>
    <script src="jquery-3.3.1.min.js"></script>
    <!--link proper js-->
    <script src="popper.js"></script>
    <!--link sweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!--link jquery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!--link bootstrap style sheet file-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <!--Script for load notifications-->
    <script>
        function load_unseen_notification(view = ''){
            $.ajax({
                url:"fetchNotification.php",
                method:"POST",
                data:{view:view},
                dataType:"json",

                success:function(data) {
                    $('.dropdown_notificaations').html(data.notification);
                    if(data.unseen_notification > 0) {
                        $('.count').html(data.unseen_notification);
                    }
                }
            });
        }
        $(document).ready(function(){
            // updating the view with notifications using ajax
            load_unseen_notification();
            // load new notifications
            $(document).on('click', '.dropdown-toggle', function(){
                $('.count').html('');
                load_unseen_notification('yes');
            });
            setInterval(function(){
                load_unseen_notification();
            }, 5000);
        });
    </script>

    <script>
        //Validate service management inputs
        function validateServiceManagementForm() {
            var date = document.forms["serviceManagement-form"]["dateService"].value;
            var vehicleNo = document.forms["serviceManagement-form"]["v_no"].value;
            var ServiceType = document.forms["serviceManagement-form"]["s_type"].value;
            var mileage = document.forms["serviceManagement-form"]["mileage"].value;
            var description = document.forms["serviceManagement-form"]["description"].value;
            var serviceCharge = document.forms["serviceManagement-form"]["s_charge"].value;
            var error_flag = false;

            //vehicle No
            if(vehicleNo=="Vehicle_No"){
                document.getElementById('error-vehicleNoService').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-vehicleNoService').style.color = "";
            }
            //date
            if(date==''){
                document.getElementById('error-dateService').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-dateService').style.color = "";
            }
            //service type
            if (ServiceType==''){
                document.getElementById('error-serviceType').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-serviceType').style.color = "";
            }
        //mileage
        if (mileage==''){
            document.getElementById('error-mileage').style.color = "red";
            error_flag = true;
        }else {
            document.getElementById('error-mileage').style.color = "";
        }
            //description
            if(description==''){
                document.getElementById('error-description').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-description').style.color = "";
            }
            //service charge
            if (/^\d{1,6}\.\d{0,2}$/.test(serviceCharge)){
                document.getElementById('error-serviceCharge').style.color = "";
            }else {
                document.getElementById('error-serviceCharge').style.color = "red";
                error_flag = true;
            }
            if (error_flag){
                return false;
            }else{
                return true;
            }
    }
    //Validate spare parts form inputs
    function validateSparePartsManagementForm() {
        var date = document.forms["sparePartsManagement-form"]["date"].value;
        var vehicleNo = document.forms["sparePartsManagement-form"]["vp_no"].value;
        var part = document.forms["sparePartsManagement-form"]["s_part"].value;
        var qunty = document.forms["sparePartsManagement-form"]["sparePartsQunty"].value;
        var units = document.forms["sparePartsManagement-form"]["sparePartsUnits"].value;
        var totalSpareParts = document.forms["sparePartsManagement-form"]["sparePartsTotal"].value;
        var error_flag = false;

        //vehicle No
        if(vehicleNo=="Vehicle_No"){
            document.getElementById('error-vehicleNo').style.color = "red";
            error_flag = true;
        }else {
            document.getElementById('error-vehicleNo').style.color = "";
        }
        //date
        if (date == '') {
            document.getElementById('error-date').style.color = "red";
            error_flag = true;
        } else {
            document.getElementById('error-date').style.color = "";
        }
        //spare part
        if (part == '') {
            document.getElementById('s_part').style.backgroundColor = "#ff5656";
            error_flag = true;
        } else {
            document.getElementById('s_part').document.body.style.backgroundColor = "";
        }
        //qunty
        if (qunty == '') {
            document.getElementById('sparePartsQunty').style.backgroundColor = "#ff5656";
            error_flag = true;
        } else {
            document.getElementById('sparePartsQunty').document.body.style.backgroundColor = "";
        }
        //units
        if (units == '') {
            document.getElementById('sparePartsUnits').style.backgroundColor = "#ff5656";
            error_flag = true;
        } else {
            document.getElementById('sparePartsUnits').document.body.style.backgroundColor = "";
        }
        //total spare part cost
        if (totalSpareParts == '') {
            document.getElementById('sparePartsTotal').style.backgroundColor = "#ff5656";
            error_flag = true;
        } else {
            document.getElementById('sparePartsTotal').document.body.style.backgroundColor = "";
        }

        if (error_flag) {
            return false;
        } else {
            return true;
        }
    }

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

<div class="">
    <!--upper nav bar-->
    <div style="background-color: black" class="w-100  float-right fixed-top">
        <!--User photo-->
        <div class="text-light mt-1 ml-3 mr-4 float-right">
            <?php
            require_once 'databaseConnector.php';
            $id =  $_SESSION['userID'];
            $query = "SELECT * FROM `user` WHERE telephone =' $id'";
            $checImage = mysqli_query($connector, $query);
            $result = mysqli_fetch_array($checImage);
            echo '<img src="data:image/jpeg;base64,'.base64_encode($result['image']).'" style="border-style: solid;border-color: #dbdbdb; border-width: 1px; border-radius: 5px; width:37px; height: 45px;"/>';
            ?>
        </div>
        <!--Logout button-->
        <div class="float-right mr-4">
            <a class="nav-link" href="logout.php" data-toggle="tooltip" title="Logout">
                <img src="dashboard_images/logout_icon.png" alt="logout icon" class="logout_logo">
            </a>
        </div>
        <!--Notification section-->
        <div class="float-right mr-4">
            <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Notifications" style="text-decoration: none; color: black">
                    <span class="count font-weight-bold" style="border-radius:10px; color: red;"></span>
                    <img src="dashboard_images/notification_icon.png" alt="Notification icon" class="notification_icon">
                </a>
                <ul class="dropdown-menu dropdown-toggle-split dropdown_notificaations mt-3 shadow-lg" style="background-color: rgb(255,255,255); text-decoration: none;"></ul>
            </div>
        </div>
        <!--Display Last login time-->
        <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-5 float-right">
            Last Login Time : <?php echo "  ". $_SESSION['lastLogin'];?>
        </div>
        <!--Display user role-->
        <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-5 float-right">
            User Role : <?php echo "  ". $_SESSION['role'];?>
        </div>
        <!--Display user name-->
        <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-5 float-right">
            <?php echo "  ". $_SESSION['firstName'] ." ".$_SESSION['lastName'];?>
        </div>
    </div>
</div>
    <!--system logo and name-->
    <div class="bg-dark border-dark h-100 w-25 fixed-top">
        <div style="background-color: black" class="pb-3 mb-3">
            <img src="dashboard_images/logo2.png" alt="YANA logo" class="w-50 mb-2 mt-4 ml-3">
            <p class="text text-secondary ml-3 titleFontSize"><b>Vehicle Fleet Management System</b></p>
        </div>

        <!--Side nav bar-->
        <nav class="">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <!--Dashboard-->
                    <li class="nav-item mt-3 pt-1 pb-1">
                        <a class="nav-link fontColor sidebarIconHide" href="dashboard.php">
                            <img src="dashboard_images/Dashboard_icon.png" alt="Settings Icon" class="dashboardIcon">
                            DASHBOARD
                        </a>
                    </li>
                    <!--Vehicle management-->
                    <li class="nav-item pt-1 pb-1">
                        <a class="nav-link active fontColor sideNavActive" href="vehicleManagement.php">
                            <img src="dashboard_images/vehicle%20Management_icon.png" alt="Vehicle management Icon" class="dashboardIcon">
                            VEHICLE MANAGEMENT
                        </a>
                    </li>
                    <!--Vehicle registration-->
                    <li class="nav-item pt-1 pb-1">
                        <a class="nav-link fontColor sidebarIconHide" href="vehicleRegistration.php">
                            <img src="dashboard_images/Untitled-1ehicle%20registration_icon.png" alt="Vehicle registration Icon" class="dashboardIcon">
                            VEHICLE REGISTRATION
                        </a>
                    </li>
                    <!--Driver registration-->
                    <li class="nav-item pt-1 pb-1">
                        <a class="nav-link fontColor sidebarIconHide" href="userRegistration.php">
                            <img src="dashboard_images/Driver%20registration_icon.png" alt="Driver registration Icon" class="dashboardIcon">
                            EMPLOYEE REGISTRATION
                        </a>
                    </li>
                    <!--Trip schedule -->
                    <li class="nav-item pt-1 pb-1">
                        <a class="nav-link fontColor sidebarIconHide" href="tripSchedule.php">
                            <img src="dashboard_images/Schedule_icon.png" alt="Trip schedule Icon" class="dashboardIcon">
                            TRIP SCHEDULE
                        </a>
                    </li>
                    <!--Fleet tracking-->
                    <li class="nav-item pt-1 pb-1">
                        <a class="nav-link fontColor sidebarIconHide" href="fleetTracking.php">
                            <img src="dashboard_images/Tracking_icon.png" alt="Fleet tracking Icon" class="dashboardIcon">
                            FLEET TRACKING
                        </a>
                    </li>
                    <!--Reports-->
                    <li class="nav-item pt-1 pb-1">
                        <a class="nav-link fontColor sidebarIconHide" href="reports.php">
                            <img src="dashboard_images/Reports_icon.png" alt="Report Icon" class="dashboardIcon">
                            REPORTS
                        </a>
                    </li>
                    <!--settings-->
                    <li class="nav-item pt-1 pb-5">
                        <a class="nav-link fontColor sidebarIconHide " href="settings_userInfo.php">
                            <img src="dashboard_images/settings_icon.png" alt="Settings Icon" class="dashboardIcon">
                            SETTINGS
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

<!--upper navigation-->
<div class="vm_upperNavContainer border border-secondary nevBackground rounded-lg shadow-sm ">
    <div>
        <!--Fuel Management-->
        <a class="nav-link text-center vm_upperNavPosition" href="fuelManagement.php">
            <div class="vm_upperNavHide">
                <img src="dashboard_images/fuel_icon.png" alt="Fuel management" class="vm_upperNavIcon">
                <p class="vm_icon_fontSize"><b>Fuel Management</b></p>
            </div>
        </a>

        <!--Service & Spare Parts Management-->
        <a class="nav-link text-center vm_upperNavPosition" href="serviceNsparePartsManagement.php">
            <div class="">
                <img src="dashboard_images/spareparts_icon.png" class="vm_upperNavIcon">
                <p class=" vm_icon_fontSize"><b>Service & Spare Parts</b></p>
            </div>
        </a>

        <!--Licence Management-->
        <a class="nav-link text-center vm_upperNavPosition" href="licenceUpdateManagement.php">
            <div class="vm_upperNavHide">
                <img src="dashboard_images/licence_icon.png" alt="Licence logo" class="vm_upperNavIcon">
                <p class="vm_icon_fontSize"><b>License Management</b></p>
            </div>
        </a>

        <!--Accident Management-->
        <a class="nav-link text-center vm_upperNavPosition" href="accidentManagement.php">
            <div class="vm_upperNavHide">
                <img src="dashboard_images/accident_icon.png" alt="Accident Logo" class="vm_upperNavIcon">
                <p class="vm_icon_fontSize"><b>Accident Management</b></p>
            </div>
        </a>

        <!--Insurance Management-->
        <a class="nav-link text-center vm_upperNavPosition" href="insuranceManagement.php">
            <div class="vm_upperNavHide">
                <img src="dashboard_images/insurance_icon.png" alt="Insurance logo" class="vm_upperNavIcon">
                <p class="vm_icon_fontSize"><b>Insurance Management</b></p>
            </div>
        </a>
    </div>
</div>
<!--Closed upper navigation-->
<!--Service n spare parts management body-->
<div class="container-fluid fm_form1_container rounded-lg shadow-sm pb-2">
    <form method="post" action="serviceAndSparepartsServer.php" name="serviceManagement-form" onsubmit="return validateServiceManagementForm()">
        <label class="text-dark"><b>Vehicle Service</b></label>
        <!--service recode inserted successfully message display-->
        <?php if (isset($_SESSION['serviceInfoInsertSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Inserted!',
                    'Record inserted successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['serviceInfoInsertSuccessfull']);
            ?>
        <?php endif ?>

        <table>
            <tr>
                <td class="tc_position3">
                    <label for="date" id="error-dateService">Date</label>
                    <input type="date" name="date" id="dateService" class="rounded-lg float-right ml-2 fuel_input_size">
                </td>
                <td class="tc_position3">
                    <label for="v_no" id="error-vehicleNoService">Vehicle Number</label>
                    <select name="vehicleNo" id="v_no" class="rounded-lg float-right ml-2 fuel_input_size">
                        <?php
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
                <td class="tc_position3">
                    <label for="s_type" id="error-serviceType">Service Type</label>
                    <input type="text" name="service_type" id="s_type" class="rounded-lg float-right ml-2 fuel_input_size">
                </td>
            </tr>
            <tr>
                <td class="tc_position3">
                    <label for="mileage" id="error-mileage">Mileage</label>
                    <input type="text" name="mileage" id="mileage" class="rounded-lg float-right ml-2 fuel_input_size">
                </td>
                <td class="tc_position3">
                    <label for="description" id="error-description">Description</label>
                    <textarea cols="15" rows="1" name="description" id="description" class="rounded-lg float-right ml-2 fuel_input_size"></textarea>
                </td>
                <td class="tc_position3">
                    <label for="s_charge" id="error-serviceCharge">Service Charge</label>
                    <input type="text" name="service_charge" id="s_charge" placeholder="5500.00" class="rounded-lg float-right ml-2 fuel_input_size">
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="">
                        <input type="submit" name="btnServiceSubmit" value="Submit" class="btn-info rounded-lg float-right rs_btn_size ml-4">
                        <input type="reset" name="reset" value="Reset" class="btn-success rounded-lg float-right rs_btn_size">
                    </div>
                </td>
            </tr>
        </table>
    </form>
    </div>

<!--spare parts section-->
<div class="container-fluid fm_form1_container rounded-lg shadow-sm pt-2 pb-2 ">
    <form method="post" action="serviceAndSparepartsServer.php" class="pb-4"  name="sparePartsManagement-form" onsubmit="return validateSparePartsManagementForm()">
        <label class="text-dark"><b>Spare Parts</b></label><br>
        <!--spare part recode inserted successfully message display-->
        <?php if (isset($_SESSION['partsInfoInsertSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Inserted!',
                    'Record inserted successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['partsInfoInsertSuccessfull']);
            ?>
        <?php endif ?>
        <table >
            <tr>
                <td class="tc_position">
                    <label for="date" id="error-date">Date</label>
                    <input type="date" name="date" id="date" class="rounded-lg float-right ml-2 fuel_input_size">
                </td>
                <td class="pl-5 tc_position">
                    <label for="vp_no" id="error-vehicleNo">Vehicle Number</label>
                    <select name="vehicleNo" id="vp_no" class="rounded-lg float-right ml-2 mr-3 fuel_input_size">
                        <?php
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
            </tr>
        </table>
    <!--spare parts table-->
        <div class="mt-3">
        <input type="submit" name="btnSparePartsSubmit" value="Submit" class="btn-info rounded-lg float-right rs_btn_size ml-4 mr-4">
        <input type="reset" name="reset" value="Reset" class="btn-success rounded-lg float-right rs_btn_size">
        </div>
    <table class="table w-50 mt-3" id="tbl_spareParts">
    <thead>
        <th class="text-center">Spare Part</th>
        <th class="text-center">Quantity</th>
        <th class="text-center">Unit Price</th>
        <th class="text-center">Total</th>
    </thead>
        <tr>
            <td><input type="text" name="part" id="s_part" class="rounded-sm sprPartsTableTxt fuel_input_size" ></td>
            <td><input type="number" name="qunty" id="sparePartsQunty" onkeyup="getTotalPartsCost()" class="rounded-sm sprPartsTableTxt numeric_value fuel_input_size" ></td>
            <td><input type="text" name="units" id="sparePartsUnits" onkeyup="getTotalPartsCost()" class="rounded-sm sprPartsTableTxt numeric_value fuel_input_size" ></td>
            <td><input type="text" name="spr_total" id="sparePartsTotal" readonly class="rounded-sm sprPartsTableTxt fuel_input_size" ></td>
        </tr>
    </table>
    </form>
</div>
<!--VEHICLE history container-->
<div class="container-fluid fm_form1_container rounded-lg shadow-sm pt-2 mb-4">
    <!--search-->
    <form method="post" action="" class="pb-4">
        <label class="text-dark pb-2"><b>Search Service and Spare parts History</b></label><br>
        <!--service recode updated successfully message display-->
        <?php if (isset($_SESSION['serviceInfoUpdateSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Updated!',
                    'Record Updated successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['serviceInfoUpdateSuccessfull']);
            ?>
        <?php endif ?>

        <!--spare parts recode updated successfully message display-->
        <?php if (isset($_SESSION['partsInfoUpdateSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Updated!',
                    'Record Updated successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['partsInfoUpdateSuccessfull']);
            ?>
        <?php endif ?>

        <table>
            <tr>
                <td class="tc_position2">
                    <select name="searchType" class="rounded-lg fuel_input_size">
                        <option value="search_type">Search Type</option>
                        <option value="services">Services</option>
                        <option value="spare_parts">Spare Parts</option>
                    </select>
                </td>
                <td class="tc_position2">
                    <select name="searchVehicleNo" id="v_no" class="rounded-lg fuel_input_size">
                        <?php
                        $searchQuery = "SELECT `regNo` FROM `vehicle`";
                        $queryResult = mysqli_query($connector, $searchQuery);
                        echo '<option>Vehicle No</option>';
                        while ($row = mysqli_fetch_assoc($queryResult)){
                            echo '<option value="'. $row['regNo'] . '">'
                                . $row['regNo'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
                <td class="tc_position2">
                    <input type="submit" name="btn_search" value="Search" class="rounded-lg btn-info ml-2 rs_btn_size ">
                </td>
            </tr>
        </table>
    </form>
    <!--history table-->
    <?php
    //check the user click search button or not
    if (isset($_POST['btn_search'])){
        //assign search type value
        $searchType = $_POST['searchType'];
        //if search value is services, then show services history table.
        if ($searchType == "services"){

            $searchVal = $_POST['searchVehicleNo'];
            $query = "SELECT * FROM `servicemanagement` WHERE vehicleNo='$searchVal' ORDER BY date DESC ";
            $listQueryResult = mysqli_query($connector,$query);

            if (mysqli_num_rows($listQueryResult)>0) {
                //showing data inside a table
                echo '<table class="table border shadow-sm">';
                echo '<tr class="thead-dark">';
                //display table columns
                echo '<th class="historyTableTxt text-center">Vehicle No</th>';
                echo '<th class="historyTableTxt text-center">Date</th>';
                echo '<th class="historyTableTxt text-center">Service Type</th>';
                echo '<th class="historyTableTxt text-center">Mileage</th>';
                echo '<th class="historyTableTxt text-center">Description</th>';
                echo '<th class="historyTableTxt text-center">Service Cost</th>';
                echo '<th class="historyTableTxt text-center"></th>';
                echo '</tr>';
                // output data of each row
                //Creates a loop to loop through results
                while ($row = mysqli_fetch_assoc($listQueryResult)) {
                    echo "<tr id='$row[id]'>
                            <td class='text historyTableTxt text-center'>" . $row['date'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['serviceType'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['mileage'] . "</td>
                            <td width='20%' class='text historyTableTxt text-center'>" . $row['description'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['total'] . "</td>
                            <td class='text historyTableTxt text-center'>
                            <button name='delete' value='D' class='btn btn-danger float-right ml-2 historyTableTxt deleteServiceRecord'>D</button>
                             <a href='serviceAndSparepartsServer.php?serviceUpdateId=$row[id] && date=$row[date] && vehicleNo=$row[vehicleNo]'><input type='submit' name='updateServiceInfo' value='U' class='btn btn-warning float-right historyTableTxt'>   
                            </td></a>
                    </tr>";
                }
                echo '</table>';
            }
            else {
                echo '<div class="ml-3 mb-4"><lable class="alert alert-danger"><b>Warning!</b> Cannot find any Records!</lable></div>';
            }

        }
        //if search value is spare parts, then show spare parts history table.
        elseif ($searchType == "spare_parts"){

            $searchVal = $_POST['searchVehicleNo'];
            $query = "SELECT * FROM `sparepartsmanagement` WHERE vehicleNo='$searchVal' ORDER BY date DESC ";
            $listQueryResult = mysqli_query($connector,$query);

            if (mysqli_num_rows($listQueryResult)>0) {
                //showing data inside a table
                echo '<table class="table border shadow-sm">';
                echo '<tr class="thead-dark">';
                //display table columns
                echo '<th class="historyTableTxt text-center">Vehicle No</th>';
                echo '<th class="historyTableTxt text-center">Date</th>';
                echo '<th class="historyTableTxt text-center">Part Name</th>';
                echo '<th class="historyTableTxt text-center">Quantity</th>';
                echo '<th class="historyTableTxt text-center">Units</th>';
                echo '<th class="historyTableTxt text-center">Total</th>';
                echo '<th class="historyTableTxt text-center"></th>';
                echo '</tr>';
                // output data of each row
                //Creates a loop to loop through results
                while ($row = mysqli_fetch_assoc($listQueryResult)) {
                    echo "<tr id='$row[id]'>
                            <td class='text historyTableTxt text-center'>" . $row['date'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['partName'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['qunty'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['units'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['total'] . "</td>
                            <td class='text historyTableTxt text-center'>
<button name='delete' value='D' class='btn btn-danger float-right ml-2 historyTableTxt deletePartRecord'>D</button>
                             <a href='sparePartsUpdateServer.php?partUpdateId=$row[id] && date=$row[date] && vehicleNo=$row[vehicleNo]'><input type='submit' name='updatePartInfo' value='U' class='btn btn-warning float-right historyTableTxt'>   
                            </td></a>
                    </tr>";
                }
                echo '</table>';
            }
            else {
                echo '<div class="ml-3 mb-4"><lable class="alert alert-danger"><b>Warning!</b> Cannot find any Records!</lable></div>';
            }
        }
        else{
            echo '<div class="ml-3 mb-4"><lable class="alert alert-danger"><b>Warning!</b> Cannot identify search type!</lable></div>';
        }
    }
    ?>
</div>
</body>
</html>

<!--Script for delete record-->
<script type="text/javascript">
    //javascript function call from class name
    $(".deleteServiceRecord").click(function(){
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
                        url: 'serviceAndSparepartsServer.php',
                        type: 'GET',
                        data: {serviceDeleteId: id},
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
                        'Service record has been deleted.',
                        'success'
                    )
                }
            })
        }
    );

    //javascript function call from class name
    $(".deletePartRecord").click(function(){
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
                        url: 'serviceAndSparepartsServer.php',
                        type: 'GET',
                        data: {partDeleteId: id},
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
                        'Spare part record has been deleted.',
                        'success'
                    )
                }
            })
        }
    );
</script>

