<?php
//Start session.
session_start();
//if username not assign to the session, this page redirect to the login page.
require_once 'databaseConnector.php';
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver") || (($_SESSION['role'])=="Maintainer") ){
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
    <title>Trip Schedule</title>
    <!--link jquery-->
    <script src="jquery-3.3.1.min.js"></script>
    <!--link proper js-->
    <script src="popper.js"></script>
    <!--link sweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!--link css file 1-->
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!--link api key-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxlO8RdvymUNRadgBp72G7UMuXKTLbTZ8&callback=initMap"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!--link api key-->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxlO8RdvymUNRadgBp72G7UMuXKTLbTZ8"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxlO8RdvymUNRadgBp72G7UMuXKTLbTZ8&libraries=places"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

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
        //Validate schedule info inputs
        function tripScheduleForm(){
            var scheduleDate = document.forms["trip-schedule-form"]["schedu_date"].value;
            var scheduleTime = document.forms["trip-schedule-form"]["schedu_time"].value;
            var vehicleNo = document.forms["trip-schedule-form"]["v_no"].value;
            var driverNo = document.forms["trip-schedule-form"]["d_number"].value;
            var startFrom = document.forms["trip-schedule-form"]["from"].value;
            var to = document.forms["trip-schedule-form"]["to"].value;
            var arrival = document.forms["trip-schedule-form"]["arr_time"].value;
            var departure = document.forms["trip-schedule-form"]["dept_time"].value;
            var description = document.forms["trip-schedule-form"]["description"].value;
            var cost = document.forms["trip-schedule-form"]["cost"].value;
            var error_flag = false;

            if (scheduleDate == ""){
                document.getElementById('error-scheduleDate').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-scheduleDate').style.color = "";
            }
            //schedule time
            if (scheduleTime == ""){
                document.getElementById('error-scheduleTime').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-scheduleTime').style.color = "";
            }
            //vehicle No
            if(vehicleNo=="Vehicle_No"){
                document.getElementById('error-vehicleNo').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-vehicleNo').style.color = "";
            }
            //driver No
            if(driverNo=="driver_number"){
                document.getElementById('error-driverNumber').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-driverNumber').style.color = "";
            }
            //from
            if (startFrom == ""){
                document.getElementById('error-from').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-from').style.color = "";
            }
            //to
            if (to == ""){
                document.getElementById('error-to').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-to').style.color = "";
            }
            //arrival
            if (arrival == ""){
                document.getElementById('error-arrTime').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-arrTime').style.color = "";
            }
            //departure
            if (departure == ""){
                document.getElementById('error-depTime').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-depTime').style.color = "";
            }
            //description
            if (description == ""){
                document.getElementById('error-description').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-description').style.color = "";
            }
            //cost per trip
            if (/^\d{1,6}\.\d{0,2}$/.test(cost)){
                document.getElementById('error-cost').style.color = "";
            }else {
                document.getElementById('error-cost').style.color = "red";
                error_flag = true;
            }
            if (error_flag){
                return false;
            }else{
                return true;
            }
        }
    </script>

    <!--Script fot load vehicle info-->
    <script>
        $(document).ready(function(){
            $('#v_no').change(function(){
                var vehicleNum = $(this).val();
                $.ajax({
                    url:"load_vehicle_data_for_schedule.php",
                    method:"POST",
                    data:{vehicleNum:vehicleNum},
                    success:function(data){
                        $('#show_vehicle').html(data);
                    }
                });
            });
        });
    </script>

    <!--Script fot load driver info-->
    <script>
        $(document).ready(function(){
            $('#d_number').change(function(){
                var driverNum = $(this).val();
                $.ajax({
                    url:"load_driver_data_for_schedule.php",
                    method:"POST",
                    data:{driverNum:driverNum},
                    success:function(data){
                        $('#show_driver').html(data);
                    }
                });
            });
        });
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
                        <a class="nav-link fontColor sideNavActive" href="tripSchedule.php">
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

<!--Trip schedule body-->
<div class="container-fluid fm_form2_container rounded-lg shadow-sm pb-2">
    <form method="post" action="tripScheduleServer.php" name="trip-schedule-form" onsubmit="return tripScheduleForm()">

        <!--schedule recode inserted successfully message display-->
        <?php if (isset($_SESSION['tripScheduleInfoInsertSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Your Schedule Number is: <?php echo $_SESSION['lastScheduleId']; ?>',
                    'Schedule created successfully. You can search your trip by schedule number.',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['tripScheduleInfoInsertSuccessfull']);
            unset($_SESSION['lastScheduleId']);
            ?>
        <?php endif ?>

        <table class="w-100">
            <tr>
                <td class="tc_position">
                    <label for="schedu_date" id="error-scheduleDate">Schedule Date</label>
                    <input type="date" name="schedu_date" id="schedu_date" class="rounded-lg ml-2 mr-3 userReg_input_size float-right">
                </td>
                <td class="tc_position">
                    <label for="schedu_time" id="error-scheduleTime">Schedule Time</label>
                    <input type="time" name="schedu_time" id="schedu_time" class="rounded-lg ml-2 mr-3 userReg_input_size float-right">
                </td>
            </tr>
            <tr>
                <td class="tc_position">
                    <label for="v_no" id="error-vehicleNo">Vehicle Number</label>
                    <select name="vehicleNo" id="v_no" class="rounded-lg ml-2 mr-3 userReg_input_size float-right">
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
                <td class="tc_position">
                    <label for="d_no" id="error-driverNumber">Driver Number</label>
                    <select name="driverNumber" id="d_number" class="rounded-lg ml-2 mr-3 userReg_input_size float-right">
                        <?php
                        $selectQueryD = "SELECT `telephone`,`fname` FROM `user` WHERE role='Driver'";
                        $queryResultD = mysqli_query($connector, $selectQueryD);
                        echo '<option value="driver_number">Phone Number</option>';
                        while ($row = mysqli_fetch_assoc($queryResultD)){
                            echo '<option value="'. $row['telephone'].'">'. $row['telephone'] ." - ".$row['fname']. '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="show_vehicle" class="container border bg-light m-3 tripScheduleSummeryBox rounded-sm p-1 float-right"></div>
                </td>
                <td>
                    <div id="show_driver" class="container border bg-light m-3 tripScheduleSummeryBox rounded-sm p-1 float-right"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr>
                <td class="tc_position">
                    <label for="from" id="error-from">Start From</label>
                    <input type="text" name="from" readonly id="from" size="30" class="rounded-lg float-right ml-2 mr-3 search_start_addr">
                    <input type="text" SIZE="30" id="searchLocation_start" class="rounded-lg float-right mt-2 ml-2 mr-3" placeholder="Type start location here">
                    <div class="input-group-append">
                        <button class="findLocation_btn get_map mt-2" type="submit">Find Location</button>
                    </div>
                    <!-- display google map -->
                    <div id="start_geomap" class="mt-3 mb-3 findLocation_map border border-secondary"></div>
                    <!-- get start lat and log -->
                    <input type="hidden" name="start_lat" class="search_start_latitude" size="30">
                    <input type="hidden" name="start_lng" class="search_start_longitude" size="30">
                </td>
                <td class="tc_position">
                    <label for="to" id="error-to">Destination</label>
                    <input type="text" name="to" readonly id="to" size="30" class="rounded-lg float-right ml-2 mr-3 search_stop_addr">
                    <input type="text" SIZE="30" id="searchLocation_stop" class="rounded-lg float-right mt-2 ml-2 mr-3" placeholder="Type destination location here">
                    <div class="input-group-append">
                        <button class="findLocation_btn get_map2 mt-2" type="submit">Find Location</button>
                    </div>
                    <!-- display google map -->
                    <div id="stop_geomap" class="mt-3 mb-3 findLocation_map border border-secondary"></div>
                    <!-- get stop lat and log -->
                    <input type="hidden" name="stop_lat" class="search_stop_latitude" size="30">
                    <input type="hidden" name="stop_lng" class="search_stop_longitude" size="30">
                </td>
            </tr>
            <tr>
                <td class="tc_position">
                    <label for="arr_time " id="error-arrTime">Departure Time</label>
                    <input type="time" name="arr_time" id="arr_time" class="rounded-lg float-right ml-2 mr-3 userReg_input_size">
                </td>
                <td class="tc_position">
                    <label for="dept_time"id="error-depTime">Arrival Time</label>
                    <input type="time" name="dept_time" id="dept_time" size="11" class="rounded-lg float-right ml-2 mr-3 userReg_input_size">
                </td>
            </tr>
            <tr>
                <td class="tc_position">
                    <label for="description" id="error-description">Description</label>
                    <textarea cols="30" rows="1" name="description" id="description" class="rounded-lg float-right ml-2 mr-3 userReg_input_size"></textarea>
                </td>
                <td class="tc_position">
                    <label for="cost" id="error-cost">Cost Per Trip</label>
                    <input type="text" name="cost" id="cost" size="11" placeholder="25000.00" class="rounded-lg float-right ml-2 mr-3 userReg_input_size">
                </td>
            </tr>
            <tr>
                <td colspan="2" class="tc_position2">
                    <div class="">
                        <input type="submit" name="submit_scheduleInfo" value="Submit" class="btn-info rounded-lg float-right rs_btn_size mt-4 ml-4 mr-3">
                        <input type="reset" name="reset" value="Reset" class="btn-success rounded-lg float-right rs_btn_size mt-4 mr-3">
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<!--search container-->
<div class="container-fluid fm_form1_container rounded-lg shadow-sm pt-2 mb-4">
    <!--search-->
    <form method="post" action="" class="pb-4">
        <label class="text-dark  pb-2"><b>Past Schedules</b></label><br>
        <table>
            <tr>
                <td class="tc_position2">
                    <label for="s_no">Schedule Number</label>
                    <input type="text" name="s_no" id="s_no" size="11" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <input type="submit" name="btn_search" value="Search" class="rounded-lg btn-info ml-2 rs_btn_size">
                </td>
                <td class="w-50">
                    <a href="tripDirectory.php" target="_blank"> <input type="button" name="btn_tripDirectory" value="Directory" class="rounded-lg float-right ml-2 btn-dark rs_btn_size"></button></a>
                </td>
            </tr>
        </table>
    </form>

    <!--past schedule info table-->
    <?php
    //search schedule
    if (isset($_POST['btn_search'])){
        $searchVal = $_POST['s_no'];
        $query_search = "SELECT * FROM `tripschedule` WHERE scheduleNo = '$searchVal'";
        $listQueryResult = mysqli_query($connector,$query_search);

        if (mysqli_num_rows($listQueryResult)>0){
            //showing data inside a table
            echo '<table class="table border shadow-sm">';
            echo '<tr class="thead-dark">';
            //display table columns
            echo '<th class="historyTableTxt text-center">Schedule No</th>';
            echo '<th class="historyTableTxt text-center">Scheduled Date</th>';
            echo '<th class="historyTableTxt text-center">Scheduled Time</th>';
            echo '<th class="historyTableTxt text-center">Vehicle No</th>';
            echo '<th class="historyTableTxt text-center">Driver No</th>';
            echo '<th class="historyTableTxt text-center">From</th>';
            echo '<th class="historyTableTxt text-center">To</th>';
            echo '<th class="historyTableTxt text-center">Departure Time</th>';
            echo '<th class="historyTableTxt text-center">Arrival Time</th>';
            echo '<th class="historyTableTxt text-center">Status</th>';
            echo '<th class="historyTableTxt text-center">Price</th>';
            echo '<th class="historyTableTxt text-center"></th>';
            echo '</tr>';
            // output data of each row
            //Creates a loop to loop through results
            while ($row= mysqli_fetch_assoc($listQueryResult)){
                echo "<tr id='$row[scheduleNo]'>
                            <td class='text historyTableTxt text-center'>" . $row['scheduleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['sdate'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['stime'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['driverNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['start'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['destination'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['arrTime'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['deptTime'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['status'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['totalPrice'] . "</td>  
                            <td class='text historyTableTxt text-center'>
                            <button name='delete' value='D' class='btn btn-danger historyTableTxt deleteRecord'>D</button></a>
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

<!--......................................J SCRIPTS............................................-->
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
                        url: 'tripScheduleServer.php',
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
                        'Schedule record has been deleted.',
                        'success'
                    )
                }
            })
        }
    );
</script>


<script>var searchBox = new google.maps.places.SearchBox(document.getElementById('searchLocation_start'));</script>
<script>var searchBox = new google.maps.places.SearchBox(document.getElementById('searchLocation_stop'));</script>

<!--Script for get start location-->
<script>
    var geocoder;
    var map;
    var marker;

    //Google Map with marker
    function initialize() {
        var initialLat = $('.search_start_latitude').val();
        var initialLong = $('.search_start_longitude').val();
        initialLat = initialLat?initialLat:6.93607010;
        initialLong = initialLong?initialLong:79.84504970;

        var latlng = new google.maps.LatLng(initialLat, initialLong);
        var options = {
            zoom: 15,
            center: latlng,
            streetViewControl: false,
            fullscreenControl: false,
            mapTypeControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("start_geomap"), options);

        geocoder = new google.maps.Geocoder();
        var icon = {
            url: "dashboard_images/start_pin.png", // image url
            scaledSize: new google.maps.Size(30, 50), // image scaled size
        };
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            icon:icon,
            position: latlng
        });

        google.maps.event.addListener(marker, "dragend", function () {
            var point = marker.getPosition();
            map.panTo(point);
            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    $('.search_start_addr').val(results[0].formatted_address);
                    $('.search_start_latitude').val(marker.getPosition().lat());
                    $('.search_start_longitude').val(marker.getPosition().lng());
                }
            });
        });

    }
    $(document).ready(function () {
        initialize();//load google map

        //autocomplete location search
        var PostCodeid = '#searchLocation_start';
        $(function () {
            $(PostCodeid).autocomplete({
                source: function (request, response) {
                    geocoder.geocode({
                        'address': request.term
                    }, function (results, status) {
                        response($.map(results, function (item) {
                            return {
                                label: item.formatted_address,
                                value: item.formatted_address,
                                lat: item.geometry.location.lat(),
                                lon: item.geometry.location.lng()
                            };
                        }));
                    });
                },
                select: function (event, ui) {
                    $('.search_start_addr').val(ui.item.value);
                    $('.search_start_latitude').val(ui.item.lat);
                    $('.search_start_longitude').val(ui.item.lon);
                    var latlng = new google.maps.LatLng(ui.item.lat, ui.item.lon);
                    marker.setPosition(latlng);
                    initialize();
                }
            });
        });

        //Point location on google map
        $('.get_map').click(function (e) {
            var address = $(PostCodeid).val();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    $('.search_start_addr').val(results[0].formatted_address);
                    $('.search_start_latitude').val(marker.getPosition().lat());
                    $('.search_start_longitude').val(marker.getPosition().lng());
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
            e.preventDefault();
        });

        //Add listener to marker for reverse geocoding
        google.maps.event.addListener(marker, 'drag', function () {
            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('.search_start_addr').val(results[0].formatted_address);
                        $('.search_start_latitude').val(marker.getPosition().lat());
                        $('.search_start_longitude').val(marker.getPosition().lng());
                    }
                }
            });
        });
    });
</script>

<!--Script for get destination location-->
<script>
    var geocoder2;
    var map2;
    var marker2;

    //Google Map with marker
    function initialize2() {
        var initialLat = $('.search_stop_latitude').val();
        var initialLong = $('.search_stop_longitude').val();
        initialLat = initialLat?initialLat:6.93607010;
        initialLong = initialLong?initialLong:79.84504970;

        var latlng = new google.maps.LatLng(initialLat, initialLong);
        var options2 = {
            zoom: 15,
            center: latlng,
            streetViewControl: false,
            fullscreenControl: false,
            mapTypeControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map2 = new google.maps.Map(document.getElementById("stop_geomap"), options2);

        geocoder2 = new google.maps.Geocoder();
        var icon2 = {
            url: "dashboard_images/stop_pin.png", // image url
            scaledSize: new google.maps.Size(30, 50), // image scaled size
        };
        marker2 = new google.maps.Marker({
            map: map2,
            draggable: true,
            icon:icon2,
            position: latlng
        });

        google.maps.event.addListener(marker2, "dragend", function () {
            var point = marker2.getPosition();
            map2.panTo(point);
            geocoder2.geocode({'latLng': marker2.getPosition()}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map2.setCenter(results[0].geometry.location);
                    marker2.setPosition(results[0].geometry.location);
                    $('.search_stop_addr').val(results[0].formatted_address);
                    $('.search_stop_latitude').val(marker2.getPosition().lat());
                    $('.search_stop_longitude').val(marker2.getPosition().lng());
                }
            });
        });

    }
    $(document).ready(function () {
        initialize2();//load google map

        //autocomplete location search
        var PostCodeid = '#searchLocation_stop';
        $(function () {
            $(PostCodeid).autocomplete({
                source: function (request, response) {
                    geocoder2.geocode({
                        'address': request.term
                    }, function (results, status) {
                        response($.map2(results, function (item) {
                            return {
                                label: item.formatted_address,
                                value: item.formatted_address,
                                lat: item.geometry.location.lat(),
                                lon: item.geometry.location.lng()
                            };
                        }));
                    });
                },
                select: function (event, ui) {
                    $('.search_stop_addr').val(ui.item.value);
                    $('.search_stop_latitude').val(ui.item.lat);
                    $('.search_stop_longitude').val(ui.item.lon);
                    var latlng = new google.maps.LatLng(ui.item.lat, ui.item.lon);
                    marker2.setPosition(latlng);
                    initialize2();
                }
            });
        });

        //Point location on google map
        $('.get_map2').click(function (e) {
            var address = $(PostCodeid).val();
            geocoder2.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map2.setCenter(results[0].geometry.location);
                    marker2.setPosition(results[0].geometry.location);
                    $('.search_stop_addr').val(results[0].formatted_address);
                    $('.search_stop_latitude').val(marker2.getPosition().lat());
                    $('.search_stop_longitude').val(marker2.getPosition().lng());
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
            e.preventDefault();
        });

        //Add listener to marker for reverse geocoding
        google.maps.event.addListener(marker2, 'drag', function () {
            geocoder2.geocode({'latLng': marker2.getPosition()}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('.search_stop_addr').val(results[0].formatted_address);
                        $('.search_stop_latitude').val(marker2.getPosition().lat());
                        $('.search_stop_longitude').val(marker2.getPosition().lng());
                    }
                }
            });
        });
    });
</script>