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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reports</title>
    <!--link jquery-->
    <script src="jquery-3.3.1.min.js"></script>
    <!--link proper js-->
    <script src="popper.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!--link bootstrap style sheet file-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!--link css file 1-->
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
        //validate report data inputs
        function validateReportsForm() {
            var reportType = document.forms["reportsForm"]["reportType"].value;
            var startDate = document.forms["reportsForm"]["startDate"].value;
            var endDate = document.forms["reportsForm"]["endDate"].value;
            var error_flag = false;

            //report type
            if (reportType =="Select Report Type"){
                document.getElementById('reportType').style.color = "red";
                error_flag = true;
            } else {
                document.getElementById('reportType').style.color = "";
            }
            //start date
            if (startDate==""){
                document.getElementById('error-startDate').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-startDate').style.color = "";
            }
            //end date
            if (endDate==""){
                document.getElementById('error-endDate').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-endDate').style.color = "";
            }

            if (error_flag){
                return false;
            }else{
                return true;
            }
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
                        <a class="nav-link active fontColor sidebarIconHide" href="vehicleManagement.php">
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
                        <a class="nav-link fontColor sideNavActive" href="reports.php">
                            <img src="dashboard_images/Reports_icon.png" alt="Report Icon" class="dashboardIcon">
                            REPORTS
                        </a>
                    </li>
                    <!--settings-->
                    <li class="nav-item pt-1 pb-5">
                        <a class="nav-link fontColor sidebarIconHide" href="settings_userInfo.php">
                            <img src="dashboard_images/settings_icon.png" alt="Settings Icon" class="dashboardIcon">
                            SETTINGS
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

<!--Reports body section-->
<div class="container-fluid fm_form2_container rounded-lg shadow-sm pb-2">
    <form class="form-group mt-3" method="post" name="reportsForm" onsubmit="return validateReportsForm()">
    <table class="w-100">
        <tr>
            <td class="tc_position">
                <select name="reportType" id="reportType" class="rounded-lg">
                    <option>Select Report Type</option>
                    <option value="FuelManagement">Fuel Management Report</option>
                    <option value="Accident">Accident Report</option>
                    <option value="TripSchedule">Trip Schedule Report</option>
                    <option value="Service">Vehicle Service Report</option>
                    <option value="SparePart">Vehicle Spare Part Report</option>
                </select>
            </td>
            <td class="tc_position">
                <label for="startDate" id="error-startDate">Start Date</label>
                <input type="date" name="startDate" id="startDate" class="rounded-sm">
            </td>
            <td class="tc_position">
                <label for="endDate" id="error-endDate">End Date</label>
                <input type="date" name="endDate" id="endDate" class="rounded-sm">
            </td>
            <td class="tc_position">
                <input type="submit" name="btn_search" value="Generate Report" class="btn btn-success ml-2 mr-4 float-right">
            </td>
        </tr>
    </table>
    </form>

    <?php
    if (isset($_POST['btn_search'])) {
        $reportType = $_POST['reportType'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        //Generate Fuel management report
        if ($reportType == "FuelManagement") {
            ?>
            <!--Save PDF-->
            <div class=" ml-2 p-2">
                <img onclick="javascript:savePdf('report')" alt="save PDF" data-toggle="tooltip" title="Save PDF"
                     class="nav  navbar-toggler-icon" src="dashboard_images/pdf%20logo.png">
            </div>
            <!--report section-->
            <div class="container" id="report">
                <!--printed report-->
                <div class="container border rounded-sm mt-3 p-3">
                    <div class="float-left">
                        <h4 class="mb-1 text-dark"><b>Fuel Management Report</b></h4>
                        <br>
                        <h6>From: &nbsp<label><?php echo $startDate; ?></label></h6>
                        <h6>To: &nbsp &nbsp &nbsp<label><?php echo $endDate; ?></label></h6>
                    </div>
                    <div class="float-right text-right">
                        <img src="dashboard_images/logo2.png" class="img-fluid reportLogo">
                        <h6 class="text-secondary"><b>Vehicle Fleet Management System</b></h6>
                        <p class=""><i>Checked Date and Time: </i><label id="date"></label><label id="time"></label></p>
                    </div>
                    <div class="float-right mt-5">

                    </div>
                    <?php
                    $query = "SELECT * FROM `fuelmanagement` WHERE `date` BETWEEN '$startDate' AND '$endDate' ORDER BY date ";
                    $listQueryResult = mysqli_query($connector, $query);

                    //showing data inside a table
                    echo '<table class="table border shadow-sm">';
                    echo '<tr class="thead-dark">';
                    //display table columns
                    echo '<th class="historyTableTxt text-center">Date</th>';
                    echo '<th class="historyTableTxt text-center">Time</th>';
                    echo '<th class="historyTableTxt text-center">Vehicle No</th>';
                    echo '<th class="historyTableTxt text-center">Fuel Type</th>';
                    echo '<th class="historyTableTxt text-center">Liters</th>';
                    echo '<th class="historyTableTxt text-center">Unit Price</th>';
                    echo '<th class="historyTableTxt text-center">Total (Rs.)</th>';
                    echo '</tr>';
                    // output data of each row
                    //Creates a loop to loop through results
                    while ($row = mysqli_fetch_assoc($listQueryResult)) {
                        echo "<tr>
                            <td class='text historyTableTxt text-center'>" . $row['date'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['time'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['fuelType'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['liters'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['unitPrice'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['total'] . "</td>
                    </tr>";
                    }
                    echo '</table>';
                    //total liters
                    $totalLitersQuery = "SELECT SUM(liters) AS liters FROM `fuelmanagement` WHERE `date` BETWEEN '$startDate' AND '$endDate'";
                    $totalLitersQueryResult = mysqli_query($connector, $totalLitersQuery);

                    if (mysqli_num_rows($totalLitersQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalLitersQueryResult)) {
                            $liters = $row['liters'];
                            echo '<lable class="font-weight-bold">Total Liters: ' . $liters . '</lable><br>';
                        }
                    }
                    //Total Fuel Cost
                    $totalFuelQuery = "SELECT SUM(total) AS total FROM `fuelmanagement` WHERE `date` BETWEEN '$startDate' AND '$endDate'";
                    $totalFuelListQueryResult = mysqli_query($connector, $totalFuelQuery);

                    if (mysqli_num_rows($totalFuelListQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalFuelListQueryResult)) {
                            $totalCost = $row['total'];
                            echo '<lable class="font-weight-bold">Total Fuel Cost: Rs. ' . $totalCost . '</lable><br>';
                        }
                    }
                    //Ave Fuel Cost
                    $aveFuelQuery = "SELECT AVG(total) AS average FROM `fuelmanagement` WHERE `date` BETWEEN '$startDate' AND '$endDate'";
                    $aveFuelQueryResult = mysqli_query($connector, $aveFuelQuery);

                    if (mysqli_num_rows($aveFuelQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($aveFuelQueryResult)) {
                            $aveCost = $row['average'];
                            echo '<lable class="font-weight-bold">Average Fuel Cost: Rs. ' . round($aveCost, 2) . '</lable>';
                        }
                    }
                    ?>
                </div>
            </div>

            <?php
        }
        ?>

        <?php
        //Generate Accident Management report
        if ($reportType == "Accident") {
            ?>
            <!--Save PDF-->
            <div class=" ml-2 p-2">
                <img onclick="javascript:savePdf('report')" alt="save PDF" data-toggle="tooltip" title="Save PDF"
                     class="nav  navbar-toggler-icon" src="dashboard_images/pdf%20logo.png">
            </div>
            <!--report section-->
            <div class="container" id="report">
                <!--printed report-->
                <div class="container border rounded-sm mt-3 p-3">
                    <div class="float-left">
                        <h4 class="mb-1 text-dark"><b>Accident Management Report</b></h4>
                        <br>
                        <h6>From: &nbsp<label><?php echo $startDate; ?></label></h6>
                        <h6>To: &nbsp &nbsp &nbsp<label><?php echo $endDate; ?></label></h6>
                    </div>
                    <div class="float-right text-right">
                        <img src="dashboard_images/logo2.png" class="img-fluid reportLogo">
                        <h6 class="text-secondary"><b>Vehicle Fleet Management System</b></h6>
                        <p class=""><i>Checked Date and Time: </i><label id="date"></label><label id="time"></label></p>
                    </div>
                    <div class="float-right mt-5">

                    </div>
                    <?php

                    $accidentQuery = "SELECT * FROM `accidentmanagement` WHERE `accDate` BETWEEN '$startDate' AND '$endDate' ORDER BY accDate ";
                    $listAccidentQueryResult = mysqli_query($connector, $accidentQuery);

                    //showing data inside a table
                    echo '<table class="table border shadow-sm">';
                    echo '<tr class="thead-dark">';
                    //display table columns
                    echo '<th class="historyTableTxt text-center">Vehicle No</th>';
                    echo '<th class="historyTableTxt text-center">Driver No</th>';
                    echo '<th class="historyTableTxt text-center">Accident Date</th>';
                    echo '<th class="historyTableTxt text-center">Accident Time</th>';
                    echo '<th class="historyTableTxt text-center">Description</th>';
                    echo '<th class="historyTableTxt text-center">Claim By</th>';
                    echo '<th class="historyTableTxt text-center">Expenses (Rs.)</th>';
                    echo '</tr>';
                    // output data of each row
                    //Creates a loop to loop through results
                    while ($row1 = mysqli_fetch_assoc($listAccidentQueryResult)) {
                        echo "<tr>
                            <td class='text historyTableTxt text-center'>" . $row1['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['driverNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['accDate'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['accTime'] . "</td>
                            <td width='20%' class='text historyTableTxt text-center'>" . $row1['description'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['claimBy'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['expences'] . "</td>
                    </tr>";
                    }
                    echo '</table>';

                    //total expenses
                    $totaExpQuery = "SELECT SUM(expences) AS expenses FROM `accidentmanagement` WHERE `accDate` BETWEEN '$startDate' AND '$endDate'";
                    $totalExpQueryResult = mysqli_query($connector, $totaExpQuery);

                    if (mysqli_num_rows($totalExpQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalExpQueryResult)) {
                            $expenses = $row['expenses'];
                            echo '<lable class="font-weight-bold">Total Expenses: ' . $expenses . '</lable><br>';
                        }
                    }
                    //Total expenses insurance
                    $totalExpInsuQuery = "SELECT SUM(expences) AS expInsuExpenses FROM `accidentmanagement` WHERE `accDate` BETWEEN '$startDate' AND '$endDate' AND claimBy='insurance' ";
                    $totalExpInsuQueryResult = mysqli_query($connector, $totalExpInsuQuery);

                    if (mysqli_num_rows($totalExpInsuQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalExpInsuQueryResult)) {
                            $totalExpInsuCost = $row['expInsuExpenses'];
                            echo '<lable class="font-weight-bold">Total Expenses Claim By Insurance: Rs. ' . $totalExpInsuCost . '</lable><br>';
                        }
                    }
                    //Total expenses orgaization
                    $totalExpOegQuery = "SELECT SUM(expences) AS expOrgExpenses FROM `accidentmanagement` WHERE `accDate` BETWEEN '$startDate' AND '$endDate' AND claimBy='organization' ";
                    $totalExpOrgQueryResult = mysqli_query($connector, $totalExpOegQuery);

                    if (mysqli_num_rows($totalExpOrgQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalExpOrgQueryResult)) {
                            $totalExpOrgCost = $row['expOrgExpenses'];
                            echo '<lable class="font-weight-bold">Total Expenses Claim By Organization: Rs. ' . $totalExpOrgCost . '</lable>';
                        }
                    }
                    ?>
                </div>
            </div>

            <?php
        }
        ?>

        <?php
        //Generate Schedule Report
        if ($reportType == "TripSchedule") {
            ?>
            <!--Save PDF-->
            <div class=" ml-2 p-2">
                <img onclick="javascript:savePdf('report')" alt="save PDF" data-toggle="tooltip" title="Save PDF"
                     class="nav  navbar-toggler-icon" src="dashboard_images/pdf%20logo.png">
            </div>
            <!--report section-->
            <div class="container" id="report">
                <!--printed report-->
                <div class="container border rounded-sm mt-3 p-3">
                    <div class="float-left">
                        <h4 class="mb-1 text-dark"><b>Trip Schedule Report</b></h4>
                        <br>
                        <h6>From: &nbsp<label><?php echo $startDate; ?></label></h6>
                        <h6>To: &nbsp &nbsp &nbsp<label><?php echo $endDate; ?></label></h6>
                    </div>
                    <div class="float-right text-right">
                        <img src="dashboard_images/logo2.png" class="img-fluid reportLogo">
                        <h6 class="text-secondary"><b>Vehicle Fleet Management System</b></h6>
                        <p class=""><i>Checked Date and Time: </i><label id="date"></label><label id="time"></label></p>
                    </div>
                    <div class="float-right mt-5">

                    </div>

                    <?php
                    $scheduleQuery = "SELECT * FROM `tripschedule` WHERE `sdate` BETWEEN '$startDate' AND '$endDate' ORDER BY sdate ";
                    $listScheduleQueryResult = mysqli_query($connector, $scheduleQuery);

                    //showing data inside a table
                    echo '<table class="table border shadow-sm">';
                    echo '<tr class="thead-dark">';
                    //display table columns
                    echo '<th class="historyTableTxt text-center">Schedule No</th>';
                    echo '<th class="historyTableTxt text-center">Schedule Date</th>';
                    echo '<th class="historyTableTxt text-center">Schedule Time</th>';
                    echo '<th class="historyTableTxt text-center">Vehicle No</th>';
                    echo '<th class="historyTableTxt text-center">Driver No</th>';
                    echo '<th class="historyTableTxt text-center">Start From</th>';
                    echo '<th class="historyTableTxt text-center">Destination</th>';
                    echo '<th class="historyTableTxt text-center">Arrival Time</th>';
                    echo '<th class="historyTableTxt text-center">Departure Time</th>';
                    echo '<th class="historyTableTxt text-center">Description</th>';
                    echo '<th class="historyTableTxt text-center">Price (Rs.)</th>';
                    echo '</tr>';
                    // output data of each row
                    //Creates a loop to loop through results
                    while ($row1 = mysqli_fetch_assoc($listScheduleQueryResult)) {
                        echo "<tr>
                            <td class='text historyTableTxt text-center'>" . $row1['scheduleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['sdate'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['stime'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['vehicleNo'] . "</td>
                            <td width='20%' class='text historyTableTxt text-center'>" . $row1['driverNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['start'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['destination'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['arrTime'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['deptTime'] . "</td>
                            <td width='20%' class='text historyTableTxt text-center'>" . $row1['description'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['totalPrice'] . "</td>
                    </tr>";
                    }
                    echo '</table>';
                    //total income
                    $totalIncomeQuery = "SELECT SUM(totalPrice) AS income FROM `tripschedule` WHERE `sdate` BETWEEN '$startDate' AND '$endDate'";
                    $totalIncomeQueryResult = mysqli_query($connector, $totalIncomeQuery);

                    if (mysqli_num_rows($totalIncomeQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalIncomeQueryResult)) {
                            $income = $row['income'];
                            echo '<lable class="font-weight-bold">Total Income From Trips: ' . $income . '</lable><br>';
                        }
                    }
                    //Number of Trips
                    $totalTripsQuery = "SELECT COUNT(scheduleNo) AS countTrips FROM `tripschedule` WHERE `sdate` BETWEEN '$startDate' AND '$endDate' ";
                    $totalTripsQueryResult = mysqli_query($connector, $totalTripsQuery);

                    if (mysqli_num_rows($totalTripsQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalTripsQueryResult)) {
                            $totalTrips = $row['countTrips'];
                            echo '<lable class="font-weight-bold">Total Trips: ' . $totalTrips . '</lable><br>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>

        <?php
        //Generate Vehicle service report
        if ($reportType == "Service") {
            ?>
            <!--Save PDF-->
            <div class=" ml-2 p-2">
                <img onclick="javascript:savePdf('report')" alt="save PDF" data-toggle="tooltip" title="Save PDF"
                     class="nav  navbar-toggler-icon" src="dashboard_images/pdf%20logo.png">
            </div>
            <!--report section-->
            <div class="container" id="report">
                <!--printed report-->
                <div class="container border rounded-sm mt-3 p-3">
                    <div class="float-left">
                        <h4 class="mb-1 text-dark"><b>Vehicles Service Report</b></h4>
                        <br>
                        <h6>From: &nbsp<label><?php echo $startDate; ?></label></h6>
                        <h6>To: &nbsp &nbsp &nbsp<label><?php echo $endDate; ?></label></h6>
                    </div>
                    <div class="float-right text-right">
                        <img src="dashboard_images/logo2.png" class="img-fluid reportLogo">
                        <h6 class="text-secondary"><b>Vehicle Fleet Management System</b></h6>
                        <p class=""><i>Checked Date and Time: </i><label id="date"></label><label id="time"></label></p>
                    </div>
                    <div class="float-right mt-5">
                    </div>

                    <?php
                    $vehicleServiceQuery = "SELECT * FROM `servicemanagement` WHERE `date` BETWEEN '$startDate' AND '$endDate' ORDER BY `date` ";
                    $vehicleServiceQueryResult = mysqli_query($connector, $vehicleServiceQuery);

                    //showing data inside a table
                    echo '<table class="table border shadow-sm">';
                    echo '<tr class="thead-dark">';
                    //display table columns
                    echo '<th class="historyTableTxt text-center">Date</th>';
                    echo '<th class="historyTableTxt text-center">Vehicle No</th>';
                    echo '<th class="historyTableTxt text-center">Service Type</th>';
                    echo '<th class="historyTableTxt text-center">Mileage</th>';
                    echo '<th class="historyTableTxt text-center">Description</th>';
                    echo '<th class="historyTableTxt text-center">Service Charge (Rs.)</th>';
                    echo '</tr>';
                    // output data of each row
                    //Creates a loop to loop through results
                    while ($row1 = mysqli_fetch_assoc($vehicleServiceQueryResult)) {
                        echo "<tr>
                            <td class='text historyTableTxt text-center'>" . $row1['date'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['serviceType'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['mileage'] . "</td>
                            <td width='20%' class='text historyTableTxt text-center'>" . $row1['description'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['total'] . "</td>
                    </tr>";
                    }
                    echo '</table>';

                    //total cost
                    $totaServiceQuery = "SELECT SUM(total) AS totalCost FROM `servicemanagement` WHERE `date` BETWEEN '$startDate' AND '$endDate'";
                    $totalServiceQueryResult = mysqli_query($connector, $totaServiceQuery);

                    if (mysqli_num_rows($totalServiceQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalServiceQueryResult)) {
                            $totalCostService = $row['totalCost'];
                            echo '<lable class="font-weight-bold">Total Expenses For Vehicle Services: '. $totalCostService . '</lable><br>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    ?>

        <?php
        //Generate Vehicle spare parts report
        if ($reportType == "SparePart") {
            ?>
            <!--Save PDF-->
            <div class=" ml-2 p-2">
                <img onclick="javascript:savePdf('report')" alt="save PDF" data-toggle="tooltip" title="Save PDF"
                     class="nav  navbar-toggler-icon" src="dashboard_images/pdf%20logo.png">
            </div>
            <!--report section-->
            <div class="container" id="report">
                <!--printed report-->
                <div class="container border rounded-sm mt-3 p-3">
                    <div class="float-left">
                        <h4 class="mb-1 text-dark"><b>Vehicles Spare Parts Report</b></h4>
                        <br>
                        <h6>From: &nbsp<label><?php echo $startDate; ?></label></h6>
                        <h6>To: &nbsp &nbsp &nbsp<label><?php echo $endDate; ?></label></h6>
                    </div>
                    <div class="float-right text-right">
                        <img src="dashboard_images/logo2.png" class="img-fluid reportLogo">
                        <h6 class="text-secondary"><b>Vehicle Fleet Management System</b></h6>
                        <p class=""><i>Checked Date and Time: </i><label id="date"></label><label id="time"></label></p>
                    </div>
                    <div class="float-right mt-5">

                    </div>
                    <?php

                    $vehiclePartsQuery = "SELECT * FROM `sparepartsmanagement` WHERE `date` BETWEEN '$startDate' AND '$endDate' ORDER BY `date` ";
                    $vehiclePartsQueryResult = mysqli_query($connector, $vehiclePartsQuery);

                    //showing data inside a table
                    echo '<table class="table border shadow-sm">';
                    echo '<tr class="thead-dark">';
                    //display table columns
                    // $columnName= mysqli_fetch_fields($listQueryResult);
                    echo '<th class="historyTableTxt text-center">Date</th>';
                    echo '<th class="historyTableTxt text-center">Vehicle No</th>';
                    echo '<th class="historyTableTxt text-center">Part Name</th>';
                    echo '<th class="historyTableTxt text-center">Quantity</th>';
                    echo '<th class="historyTableTxt text-center">Units</th>';
                    echo '<th class="historyTableTxt text-center">Total Cost (Rs.)</th>';
                    echo '</tr>';
                    // output data of each row
                    //Creates a loop to loop through results
                    while ($row1 = mysqli_fetch_assoc($vehiclePartsQueryResult)) {
                        echo "<tr>
                            <td class='text historyTableTxt text-center'>" . $row1['date'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['partName'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['qunty'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['units'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row1['total'] . "</td>
                    </tr>";
                    }
                    echo '</table>';

                    //total cost
                    $totaPartQuery = "SELECT SUM(total) AS totalCost FROM `sparepartsmanagement` WHERE `date` BETWEEN '$startDate' AND '$endDate'";
                    $totalPartQueryResult = mysqli_query($connector, $totaPartQuery);

                    if (mysqli_num_rows($totalPartQueryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($totalPartQueryResult)) {
                            $totalCostParts = $row['totalCost'];
                            echo '<lable class="font-weight-bold">Total Expenses For Vehicle Spare Parts: '. $totalCostParts . '</lable><br>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }
    ?>

</div>

<!--......................................J SCRiPTS............................................-->

<script>
    //Save as PDF
    function savePdf(divID){
        var divElements = document.getElementById(divID).innerHTML;
        var oldPage = document.body.innerHTML;

        document.body.innerHTML = "<html><head><title></title></head> <body>" + divElements + "</body></html>";
        window.print();
        document.body.innerHTML = oldPage;
    }
    //Display date and time
    window.onload = setInterval(clock,1000);
    function clock(){
        var d = new Date();
        var date = d.getDate();
        var year = d.getFullYear();
        var month = d.getMonth();
        var monthArr = ["January", "February","March", "April", "May", "June", "July", "August", "September", "October", "November","December"];
        month = monthArr[month];
        document.getElementById("date").innerHTML=date+" "+month+" "+year+"&nbsp &nbsp";

        var t = new Date();
        var hours = t.getHours();
        var min = t.getMinutes();
        var sec = t.getSeconds();
        document.getElementById("time").innerHTML=hours+":"+min+":"+sec;

    }
</script>
</body>
