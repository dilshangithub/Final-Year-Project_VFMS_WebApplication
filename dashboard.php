<?php
//Start session.
session_start();
include_once 'databaseConnector.php';
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver")){
    header('location:logout.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <script src="popper.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="extraCss.css">
    <script src="jquery-3.3.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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
            //automatically check new notifications
            setInterval(function(){
                load_unseen_notification();
            }, 5000);
        });
    </script>

    <!--Accident management chart-->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['claimBy','total'],
                <?php

                $query = "SELECT COUNT(id) AS total,claimBy FROM `accidentmanagement` where YEAR(`accDate`)=YEAR(NOW()) AND MONTH(`accDate`)=MONTH(NOW()) GROUP BY claimBy";
                $accResult = mysqli_query($connector,$query);
                while($row = mysqli_fetch_array($accResult)){
                    echo "['".$row['claimBy']."',".$row['total']."],";
                }
                ?>
            ]);
            var options = {
                width: 300,
                height: 300,
                colors: ['#42e0cb', '#3794e6'],
                legend: { position: 'bottom' },
                is3D: true
            };
            var chart = new google.visualization.PieChart(document.getElementById('AccidentClaimSummery'));
            chart.draw(data, options);
        }
    </script>

    <!--past 30 days fuel management chart-->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Date', 'Total'],
                <?php
                $query = "SELECT date, SUM(total) AS TotalCost FROM fuelmanagement WHERE date BETWEEN NOW() - INTERVAL 30 DAY AND NOW() GROUP BY date";

                $fuelResult = mysqli_query($connector,$query);
                while($row = mysqli_fetch_array($fuelResult)){
                    $date = $row['date'];
                    $total = $row['TotalCost'];
                ?>
            ['<?php echo $date;?>',<?php echo $total;?>],
                <?php
                }
                ?>
            ]);
            var options = {
                width: 950,
                height: 450,
                colors: ['#ce0200'],
                legend: {position: 'none'}
            };
            var chart = new google.visualization.LineChart(document.getElementById('FuelCostSummary'));
            chart.draw(data, options);
        }
    </script>

    <!--number of trip schedules chart-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['date', 'total Trips'],
                <?php
//                $query = "SELECT sdate, COUNT(totalPrice) AS counter FROM `tripschedule`where YEAR(`sdate`)=YEAR(NOW()) AND WEEK(`sdate`)=WEEK(NOW()) GROUP BY sdate";
                $query = "SELECT sdate, COUNT(totalPrice) AS counter FROM `tripschedule`WHERE sdate BETWEEN NOW() - INTERVAL 7 DAY AND NOW() GROUP BY sdate";

                $scheduleResult = mysqli_query($connector,$query);
                while($row = mysqli_fetch_array($scheduleResult)){
                $date = $row['sdate'];
                $total = $row['counter'];
                ?>
                ['<?php echo $date;?>',<?php echo $total;?>],
                <?php
                }
                ?>
            ]);

            var options = {
                width: 300,
                height: 275,

                colors: ['#e68e76'],
                legend: {position: 'none'}
            };

            var chart = new google.charts.Bar(document.getElementById('totalSchedules'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
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
                        <a class="nav-link fontColor  sideNavActive" href="dashboard.php">
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
                        <a class="nav-link fontColor sidebarIconHide" href="reports.php">
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

<!--Start Dashboard-->
<div class="border border-secondary w-auto mr-4 bodyContainer nevBackground rounded-lg shadow-sm ">
    <!--Vehicle container-->
    <div class="dropdown-dashboard-containers">
    <div class="box_dashboard box_dashboard_color1 box_siz_dashboard rounded-lg ml-4">
        <div class="box_content_padding">
            <?php
            //count total number of vehicles.
            $query = "SELECT COUNT(regNo) AS counter FROM vehicle";
            $vehicleResult = mysqli_query($connector,$query);
            $row = mysqli_fetch_array($vehicleResult);
            ?>
            <h2><b><?php echo $row['counter'];?></b></h2>
            <h5><b>Vehicles</b></h5>
            <img src="dashboard_images/vehicle_vector.png" alt="vehicle icon" class="boxIcon">

            <!--Show vehicle info when mouse hover-->
                <div class="dropdown-content dropdown_scroll_dashboard">
                    <?php
                    $selectQuery = "SELECT * FROM `vehicle`";
                    $queryResult = mysqli_query($connector, $selectQuery);

                    //display results.
                    while ($row = mysqli_fetch_assoc($queryResult)){
                       echo '<a href="#" class="p-2">'.'Plate: '.$row['regNo'].'&nbsp; &nbsp; &nbsp;'.'Vehicle Type: '.$row['type'].'&nbsp; &nbsp; &nbsp;'.'Make: '.$row['make'].'&nbsp; &nbsp; &nbsp;'.'Container Type: '.$row['containerType'].'</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!--Driver container-->
    <div class="dropdown-dashboard-containers">
    <div class="box_dashboard box_dashboard_color2 box_siz_dashboard  rounded-lg ml-4">
        <div class="box_content_padding">
            <?php
            //get total number of drivers
            $query = "SELECT COUNT(nic) AS counter FROM `user` WHERE role='Driver' ";
            $driverResult = mysqli_query($connector,$query);
            $row = mysqli_fetch_array($driverResult);
            ?>
            <h2><b><?php echo $row['counter'];?></b></h2>
            <h5><b>Drivers</b></h5>
            <img src="dashboard_images/person_vector.png" alt="person icon" class="boxIcon">

            <!--Show driver info when mouse hover-->
                <div class="dropdown-content dropdown_scroll_dashboard">
                    <?php
                    $selectQuery = "SELECT * FROM `user` WHERE role='Driver'";
                    $queryResult = mysqli_query($connector, $selectQuery);

                    //display results
                    while ($row = mysqli_fetch_assoc($queryResult)){
                        echo '<a href="#" class="p-2">'.'Name: '.$row['fname'].'&nbsp;'.$row['lname'].'&nbsp; &nbsp; &nbsp;'.'Telephone: '.$row['telephone'].'&nbsp; &nbsp; &nbsp;'.'NIC: '.base64_decode($row['nic']).'</a>';
                    }
                    ?>
            </div>
        </div>
    </div>
    </div>

    <!--Schedules-->
    <div class="dropdown-dashboard-containers">
    <div class="box_dashboard box_dashboard_color3 box_siz_dashboard  rounded-lg ml-4">
        <div class="box_content_padding">
            <?php
            $query = "SELECT COUNT(scheduleNo) AS counter FROM tripschedule";
            $scheduleResult = mysqli_query($connector,$query);
            $row = mysqli_fetch_array($scheduleResult);
            ?>
            <h2><b><?php echo $row['counter'];?></b></h2>
            <h5><b>Schedules</b></h5>
            <img src="dashboard_images/schedule_dashboard_box_icon.png" alt="schedule icon" class="boxIcon">

            <!--Show schedule info when mouse hover-->
            <div class="dropdown-content">
                <?php
                //get total number of not_completed schedules.
                $selectQuery = "SELECT COUNT(scheduleNo) AS counter1 FROM tripschedule WHERE status = 'notCompleted'";
                $queryResult = mysqli_query($connector, $selectQuery);
                $row = mysqli_fetch_assoc($queryResult);

                //get total number of completed schedules.
                $query2 = "SELECT COUNT(scheduleNo) AS counter2 FROM tripschedule WHERE status = 'completed'";
                $scheduleResultComp = mysqli_query($connector,$query2);
                $row2 = mysqli_fetch_array($scheduleResultComp);

                //Display results
                    echo '<a href="#" class="p-2">'.'Not Completed Schedules: '.$row['counter1'].'</a>';
                    echo '<a href="#" class="p-2">'.'Completed Schedules: '.$row2['counter2'].'</a>';
                ?>
            </div>
        </div>
    </div>
    </div>

    <!--Warnings-->
    <div class="dropdown-dashboard-containers">
    <div class="box_dashboard box_dashboard_color4 box_siz_dashboard  rounded-lg ml-4">
        <div class="box_content_padding">
            <?php
            //count total number of warnings before two weeks - driver's licence expired date.
            $queryCount1_1 = "SELECT COUNT(driverNo) AS counter1_1 FROM driver WHERE driver.lexpireDate >= DATE(now()) AND driver.lexpireDate <= DATE_ADD(DATE(now()), INTERVAL 2 WEEK)";
            $resultCounter1_1 = mysqli_query($connector,$queryCount1_1);
            $row = mysqli_fetch_array($resultCounter1_1);
            $counter1_1 = $row['counter1_1'];

            //count total number of warnings after driver's licence expired date.
            $queryCount1_11 = "SELECT COUNT(driverNo) AS counter1_11 FROM driver WHERE driver.lexpireDate < DATE(now())";
            $resultCounter1_11 = mysqli_query($connector,$queryCount1_11);
            $row = mysqli_fetch_array($resultCounter1_11);
            $counter1_11 = $row['counter1_11'];

            //count total number of warnings before two weeks - driver's insurance expired date.
            $queryCount1_2 = "SELECT COUNT(driverNo) AS counter1_2 FROM driver WHERE driver.iexpireDate >= DATE(now()) AND driver.iexpireDate <= DATE_ADD(DATE(now()), INTERVAL 2 WEEK)";
            $resultCounter1_2 = mysqli_query($connector,$queryCount1_2);
            $row = mysqli_fetch_array($resultCounter1_2);
            $counter1_2 = $row['counter1_2'];

            //count total number of warnings after driver's insurance expired date.
            $queryCount1_22 = "SELECT COUNT(driverNo) AS counter1_22 FROM driver WHERE driver.iexpireDate < DATE(now())";
            $resultCounter1_22 = mysqli_query($connector,$queryCount1_22);
            $row = mysqli_fetch_array($resultCounter1_22);
            $counter1_22 = $row['counter1_22'];

            //count total number of warnings before two weeks - insurance expired date.
            $queryCount1 = "SELECT COUNT(vehicleNo) AS counter1 FROM insurancemanagement WHERE insurancemanagement.iexpireDate >= DATE(now()) AND insurancemanagement.iexpireDate <= DATE_ADD(DATE(now()), INTERVAL 2 WEEK)";
            $resultCounter1 = mysqli_query($connector,$queryCount1);
            $row = mysqli_fetch_array($resultCounter1);
            $counter1 = $row['counter1'];

            //count total number of warnings after insurance expired date.
            $queryCount11 = "SELECT COUNT(vehicleNo) AS counter11 FROM insurancemanagement WHERE insurancemanagement.iexpireDate < DATE(now())";
            $resultCounter11 = mysqli_query($connector,$queryCount11);
            $row = mysqli_fetch_array($resultCounter11);
            $counter11 = $row['counter11'];

            //count total number of warnings before two weeks - licence expired date.
            $queryCount2 = "SELECT COUNT(vehicleNo) AS counter2 FROM licencemanagement WHERE licencemanagement.expireDate >= DATE(now()) AND licencemanagement.expireDate <= DATE_ADD(DATE(now()), INTERVAL 2 WEEK)";
            $resultCounter2 = mysqli_query($connector,$queryCount2);
            $row = mysqli_fetch_array($resultCounter2);
            $counter2 = $row['counter2'];

            //count total number of warnings after licence expired date.
            $queryCount22 = "SELECT COUNT(vehicleNo) AS counter22 FROM licencemanagement WHERE licencemanagement.expireDate < DATE(now())";
            $resultCounter22 = mysqli_query($connector,$queryCount22);
            $row = mysqli_fetch_array($resultCounter22);
            $counter22 = $row['counter22'];

            //calculate total warnings
            $totalDriverLicenceWarnings = ($counter1_1 + $counter1_11);
            $totalDriverInsuranceWarnings = ($counter1_2 + $counter1_22);
            $totalInsuranceWarnings = ($counter1 + $counter11);
            $totalLicenceWarnings = ($counter2 + $counter22);
            $totalCountsOfWarnings = ($totalInsuranceWarnings + $totalLicenceWarnings + $totalDriverLicenceWarnings + $totalDriverInsuranceWarnings);
            ?>
            <h2><b><?php echo $totalCountsOfWarnings;?></b></h2>
            <h5><b>Warnings</b></h5>
            <img src="dashboard_images/warning_vector.png" alt="warning icon" class="boxIcon">

            <!--Show warning info when mouse hover-->
            <div class="dropdown-content dropdown_scroll_dashboard_warnings">
                <?php
                //display warning notification of driver's insurance expired date (before two weeks)
                $selectQuery = "SELECT * FROM driver WHERE driver.iexpireDate >= DATE(now()) AND driver.iexpireDate <= DATE_ADD(DATE(now()), INTERVAL 2 WEEK)";
                $queryResult = mysqli_query($connector, $selectQuery);
                while ($row = mysqli_fetch_assoc($queryResult)){
                    echo "<a href='updateDriver.php?updateId=$row[driverNo]' class='p-2'>".'Insurance of Driver No '.$row['driverNo'].' Will Expire on '.$row['iexpireDate']."</a>";
                }

                //display warning notification of driver's insurance expired date (after expired)
                $selectQuery = "SELECT * FROM driver WHERE driver.iexpireDate < DATE(now())";
                $queryResult = mysqli_query($connector, $selectQuery);
                while ($row = mysqli_fetch_assoc($queryResult)){
                    echo "<a href='updateDriver.php?updateId=$row[driverNo]' class='p-2'>".'Insurance of Driver No '.$row['driverNo'].' Expired on '.$row['iexpireDate'].' and <b>Please update driver insurance!</b>'."</a>";
                }

                //display warning notification of driver's licence expired date (before two weeks)
                $selectQuery = "SELECT * FROM driver WHERE driver.lexpireDate >= DATE(now()) AND driver.lexpireDate <= DATE_ADD(DATE(now()), INTERVAL 2 WEEK)";
                $queryResult = mysqli_query($connector, $selectQuery);
                while ($row = mysqli_fetch_assoc($queryResult)){
                    echo "<a href='updateDriver.php?updateId=$row[driverNo]' class='p-2'>".'Licence of Driver No '.$row['driverNo'].' Will Expire on '.$row['lexpireDate']."</a>";
                }

                //display warning notification of driver's licnece expired date (after expired)
                $selectQuery = "SELECT * FROM driver WHERE driver.lexpireDate < DATE(now())";
                $queryResult = mysqli_query($connector, $selectQuery);
                while ($row = mysqli_fetch_assoc($queryResult)){
                    echo "<a href='updateDriver.php?updateId=$row[driverNo]' class='p-2'>".'Licence of Driver No '.$row['driverNo'].' Expired on '.$row['lexpireDate'].' and <b>Please update driver licence!</b>'."</a>";
                }

                //display warning notification of insurance expired date (before two weeks)
                $selectQuery = "SELECT * FROM insurancemanagement WHERE insurancemanagement.iexpireDate >= DATE(now()) AND insurancemanagement.iexpireDate <= DATE_ADD(DATE(now()), INTERVAL 2 WEEK)";
                $queryResult = mysqli_query($connector, $selectQuery);
                while ($row = mysqli_fetch_assoc($queryResult)){
                    echo "<a href='insuranceManagementServer.php?updateId=$row[id] && vehicleNo=$row[vehicleNo]' class='p-2'>".'Insurance of Vehicle No '.$row['vehicleNo'].' Will Expire on '.$row['iexpireDate']."</a>";
                }

                //display warning notification of insurance expired date (after expired)
                $selectQuery = "SELECT * FROM insurancemanagement WHERE insurancemanagement.iexpireDate < DATE(now())";
                $queryResult = mysqli_query($connector, $selectQuery);
                while ($row = mysqli_fetch_assoc($queryResult)){
                    echo "<a href='insuranceManagementServer.php?updateId=$row[id] && vehicleNo=$row[vehicleNo]' class='p-2'>".'Insurance of Vehicle No '.$row['vehicleNo'].' Expired on '.$row['iexpireDate'].' and <b>Please Re-insurance the vehicle and update the system!</b>'."</a>";
                }

                //display warning notification of licence expired date (before two weeks)
                $selectQuery = "SELECT * FROM licencemanagement WHERE licencemanagement.expireDate >= DATE(now()) AND licencemanagement.expireDate <= DATE_ADD(DATE(now()), INTERVAL 2 WEEK)";
                $queryResult = mysqli_query($connector, $selectQuery);
                while ($row = mysqli_fetch_assoc($queryResult)){
                    echo "<a href='licenceUpdateServer.php?updateId=$row[id] && vehicleNo=$row[vehicleNo]' class='p-2'>".'Licence of Vehicle No '.$row['vehicleNo'].' Will Expire on '.$row['expireDate']."</a>";
                }

                //display warning notification of licence expired date (after expired)
                $selectQuery = "SELECT * FROM licencemanagement WHERE licencemanagement.expireDate < DATE(now())";
                $queryResult = mysqli_query($connector, $selectQuery);
                while ($row = mysqli_fetch_assoc($queryResult)){
                    echo "<a href='licenceUpdateServer.php?updateId=$row[id] && vehicleNo=$row[vehicleNo]' class='p-2'>".'Licence of Vehicle No '.$row['vehicleNo'].' Expired on '.$row['expireDate'].' and <b>Please Re-license the vehicle and update the system!</b>'."</a>";
                }
                ?>
            </div>
        </div>
    </div>
    </div>
</div>

<!--Graphs Container-->
<div class="dashboard_graph_container w-auto mr-4 pt-3 h-auto">

    <table class="table w-100">
        <tr class="text-dark font-weight-bold">
            <td style="font-size: 15px" class="text-center">
                <label>Accident Claim Summary on This Month</label>
            </td>
            <td style="font-size: 15px" class="text-center">
                <label>Total Trip Schedules on Last 07 Days</label>
            </td>
            <td style="font-size: 15px" class="text-center">
                <label>Overall Summary</label>
            </td>
        </tr>
        <tr>
            <td>
                <div id="AccidentClaimSummery"></div>
            </td>
            <td>
                <div id="totalSchedules"></div>
            </td>
            <td colspan="2">
                <div>
                    <!--total revenue-->
                    <div style="background-color: #00ce08; width: 250px" class="p-1 ml-5 border shadow-sm border-secondary text-center font-weight-bold rounded-sm">
                        <label style="font-size: 12px"><h5><b>Total Revenue</b></h5>From Completed Trips on This Month</label>
                        <?php
                        $query = "SELECT SUM(totalPrice) AS total FROM tripschedule where YEAR(`sdate`)=YEAR(NOW()) AND MONTH(`sdate`)=MONTH(NOW()) AND status = 'completed'";

                        $totalTripResult = mysqli_query($connector,$query);
                        while($row = mysqli_fetch_array($totalTripResult)) {
                            $total = $row['total'];
                        }
                        ?>
                        <label style="font-size: 25px; color: white">Rs.<?php echo "  ". round($total,2);?></label>
                    </div>

                    <!--total fuel Consumption-->
                    <div style="background-color: #cec700; width: 250px" class="p-1 ml-5 border mt-3 shadow-sm border-secondary text-center font-weight-bold rounded-sm">
                        <label style="font-size: 12px"><h5><b>Total Fuel Consumption</b></h5>on This Month</label>
                        <?php
                        $query = "SELECT SUM(total) AS total FROM fuelmanagement where YEAR(`date`)=YEAR(NOW()) AND MONTH(`date`)=MONTH(NOW())";
                        $totalFuelResult = mysqli_query($connector,$query);
                        while($row = mysqli_fetch_array($totalFuelResult)) {
                            $total = $row['total'];
                        }
                        ?>
                        <label style="font-size: 25px; color: white">Rs.<?php echo "  ". round($total,2);?></label>
                    </div>

                    <!--average fuel Consumption-->
                    <div style="background-color: #ce91aa; width: 250px" class="p-1 ml-5 border mt-3 shadow-sm border-secondary text-center font-weight-bold rounded-sm">
                        <label style="font-size: 12px"><h5><b>Average Fuel</b></h5>Consumption per Vehicle on This Month</label>
                        <?php
                        $totalLitersQuery = "SELECT SUM(total) AS total FROM fuelmanagement where YEAR(`date`)=YEAR(NOW()) AND MONTH(`date`)=MONTH(NOW())";
                        $totalFuelResult = mysqli_query($connector,$totalLitersQuery);
                        while($row = mysqli_fetch_array($totalFuelResult)) {
                            $totalLiters = $row['total'];
                        }
                        $totalVehicleQuery = "SELECT COUNT(regNo) AS totalVehicles FROM vehicle";
                        $totalVehicleResult = mysqli_query($connector,$totalVehicleQuery);
                        while($row2 = mysqli_fetch_array($totalVehicleResult)) {
                            $totaVehicles = $row2['totalVehicles'];

                            $aveLitersPerVehicle = ($totalLiters / $totaVehicles);
                        }
                        ?>
                        <label style="font-size: 25px; color: white">Rs. <?php echo "  ". round($aveLitersPerVehicle,2);?></label>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="text-dark font-weight-bold">
            <td colspan="3" style="font-size: 15px" class="text-center">
                <label>Fuel Consumption on Last 30 Days</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="FuelCostSummary"></div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>