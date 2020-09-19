<?php
//Start session.
session_start();
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver")){
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
    <title>Fleet Tracking</title>
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
        //Validate tracking form
        function validateTrackingForm() {
            var vehicleNo = document.forms["tracking-form"]["v_no"].value;
            var error_flag = false;

            //vehicle No
            if(vehicleNo=="Vehicle_No"){
                document.getElementById('error-vehicleNo').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-vehicleNo').style.color = "";
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
                    <a class="nav-link fontColor sideNavActive" href="fleetTracking.php">
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

<!--fleet tracking body-->
<div class="container-fluid fm_form2_container rounded-lg shadow-sm pb-2">
    <!--Fleet tracking content-->
    <div class="container mt-3">
        <form class="form-group" method="post" action="" name="tracking-form" onsubmit="return  validateTrackingForm()">
            <label for="v_no" id="error-vehicleNo" class="mr-3">Select Vehicle Number</label>
            <select name="vehicleNo" id="v_no" class="rounded-lg ml-2 mr-3">
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
            <input type="submit" name="btn_tracking" value="Track" class="btn btn-info ml-3">
        </form>
    </div>
    <!--map section-->
    <div id="mapSection" class="container border rounded-sm pt-2 pb-2">
        <!--preview map-->
        <div id="googleMap" class="rounded-sm" style="width:100%;height:450px;"></div>
    </div>
</div>

<?php
require_once "databaseConnector.php";

if (isset($_POST['btn_tracking'])) {
    $trackingID = $_POST['vehicleNo'];
    //get tracking data from database
    $selectQuery = "SELECT vehicleNo,gpscordinate.date,time,latitude,longitude FROM allocatemobile INNER JOIN gpscordinate ON allocatemobile.imei = gpscordinate.imei WHERE vehicleNo = '$trackingID'";
    $queryResult = mysqli_query($connector, $selectQuery);
        while ($row = mysqli_fetch_assoc($queryResult)) {
            $lat = $row['latitude'];
            $lng = $row['longitude'];
            $vehicleNo = $row['vehicleNo'];
            $date = $row['date'];
            $time = $row['time'];
        }
    }
?>

<!--for load map-->
<script type="text/javascript">

        function tracking() {
            var location = {lat: <?php echo $lat;?>  , lng: <?php echo $lng;?>  };
            var map = new google.maps.Map(document.getElementById('googleMap'),
                {
                    zoom: 15,
                    center: location,
                    mapTypeControl: false,
                    streetViewControl: false,
                    mapTypeId: 'roadmap'
                });
            //The marker, positioned at vehicle location
            var icon = {
                url: "dashboard_images/trackingLogo.png", // image url
                scaledSize: new google.maps.Size(50, 50), // image scaled size
            };
            //set marker to location.
            var marker = new google.maps.Marker({
                position: location,
                map: map,
                icon: icon,
                title: '<?php echo 'Vehicle No ' . $vehicleNo . '\n' . 'Date: ' . $date . '\n' . 'Time: ' . $time?>',
                visible: true
            });
            marker.setMap(map);

            setInterval(function () {
                $.ajax({
                    type:'POST',
                    url:'fleetTrakingUpdate.php',
                    data: {vid:'<?php echo $vehicleNo?>'},

                    success:function(xhrob,status,value){
                       var dataAjax = JSON.parse(value.responseText);
                       console.log("updated.."+parseFloat(dataAjax.lat));
                        marker.setMap(null);

                       marker = new google.maps.Marker({
                            position:  { lat: parseFloat(dataAjax.lat)  , lng: parseFloat(dataAjax.lng) },
                            map: map,
                            icon: icon,
                            title:  "Vehicle No "+ dataAjax.vehicleNo + " \n " + "Date: "+ dataAjax.date + "\n" + "Time: "+dataAjax.time +";",
                            visible: true
                        });
                        marker.setMap(map);
                        map.setCenter(marker.getPosition());
                    },
                        error:function(jxhrObject,rescode,restext){
                    }
                })
            }, 3500);
        }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxlO8RdvymUNRadgBp72G7UMuXKTLbTZ8&callback=tracking"></script>

</body>
</html>