<?php
//Start session.
session_start();
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Settings-User Information Update</title>
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
        //Validate user info inputs
        function updateUserInfo() {
            var fname = document.forms["updateUserInfo-Table"]["Fname"].value;
            var lname = document.forms["updateUserInfo-Table"]["Lname"].value;
            var address = document.forms["updateUserInfo-Table"]["address"].value;
            var email = document.forms["updateUserInfo-Table"]["email"].value;
            var photo = document.forms["updateUserInfo-Table"]["photo"].value;
            var error_flag = false;

            //first name
            if (/^[a-zA-Z]+$/.test(fname)){
                document.getElementById('error-fname').style.color = "";
            }else {
                document.getElementById('error-fname').style.color = "red";
                error_flag = true;
            }
            //last name
            if (/^[a-zA-Z]+$/.test(lname)){
                document.getElementById('error-lname').style.color = "";
            }else {
                document.getElementById('error-lname').style.color = "red";
                error_flag = true;
            }
            //address
            if (address==''){
                document.getElementById('error-address').style.color = "red";
                error_flag = true;
            }
            else{
                document.getElementById('error-address').style.color = "";
            }
            //email
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
                document.getElementById('error-email').style.color = "";
            }else {
                document.getElementById('error-email').style.color = "red";
                error_flag = true;
            }
            //image
            if(photo == ''){
                document.getElementById('error-photo').style.color = "red";
                error_flag = true;
            }else{
                var extension = photo.substring(photo.lastIndexOf('.')+1).toLowerCase();
                if(extension == "gif"||extension == "png"||extension == "bmp"||extension == "jpeg"||extension == "jpg"){
                    document.getElementById('error-d-image').style.color = "";
                }else {
                    document.getElementById('image_errorMsg').innerHTML='File Formats: GIF/PNG/BMP/JPEG/JPG';
                    error_flag = true;
                }
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
<!--Template-->
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
                        <a class="nav-link fontColor sidebarIconHide" href="reports.php">
                            <img src="dashboard_images/Reports_icon.png" alt="Report Icon" class="dashboardIcon">
                            REPORTS
                        </a>
                    </li>
                    <!--settings-->
                    <li class="nav-item pt-1 pb-5">
                        <a class="nav-link fontColor sideNavActive" href="settings_userInfo.php">
                            <img src="dashboard_images/settings_icon.png" alt="Settings Icon" class="dashboardIcon">
                            SETTINGS
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

<!--Settings body-->
<div class="container-fluid fm_form2_container rounded-lg shadow-sm pb-2">
    <!--Settings nav bar-->
    <ul class="nav nav-pills mt-2">
        <li class="nav-item shadow-sm w-25"><a class="nav-link active btn-secondary" href="settings_userInfo.php">Update User Information</a></li>
        <li class="nav-item shadow-sm w-25"><a class="nav-link text-dark nevBackground pl-3" href="settings_changePassword.php">Change Password</a></li>
        <li class="nav-item shadow-sm w-25"><a class="nav-link text-dark nevBackground pl-3" href="settings_backup.php">Backup</a></li>
    </ul>

    <!--userInfo container-->
    <div class="ml-4 mt-5 ml-5 pl-5">
        <?php
        //get id of update recode
        $updateId =  $_SESSION['userID'];

            if (isset($_POST['update_userInfo'])) {
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $address = $_POST['address'];
                $email = $_POST['email'];
                $imgData = addslashes (file_get_contents($_FILES['photo']['tmp_name']));
                $change_pwd_error = 'Record Updated successfully!';

                $updateUserInfoQuery = "UPDATE `user` SET `fname`='$fname',`lname`='$lname',`address`='".base64_encode($address)."',`email`='".base64_encode($email)."',`image`='$imgData' WHERE telephone = '$updateId'";

                $updateQueryResult = mysqli_query($connector, $updateUserInfoQuery) or die();
                echo '
             <div>
             <div class="alert alert-success w-75 ml-5">' . $change_pwd_error . '</div>
             <div>';
            }

        //get current values from database
        $selectQuery = "SELECT * FROM user WHERE telephone = '$updateId'";
        $queryResult = mysqli_query($connector, $selectQuery);
        while ($row = mysqli_fetch_row($queryResult)) {
        ?>
        <form class="form-group mb-4 ml-5 pl-5" method="post" action="" enctype="multipart/form-data" name="updateUserInfo-Table" onsubmit="return updateUserInfo()">
            <table>
                <tr>
                    <td class="tc_position">
                        <label for="Fname" id="error-fname">First Name</label>
                        <input type="text" name="fname" value="<?php echo $row[0]; ?>" id="Fname" size="40" class="rounded-sm float-right ml-3 text-secondary">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="Lname" id="error-lname">Last Name</label>
                        <input type="text" name="lname" value="<?php echo $row[1]; ?>" id="Lname" size="40" class="rounded-sm float-right ml-3 text-secondary">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="address" id="error-address">Address</label>
                        <input type="text" name="address" value="<?php echo base64_decode($row[3]); ?>" id="address" size="40" class="rounded-sm float-right ml-3 text-secondary">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="email" id="error-email">Email Address</label>
                        <input type="text" name="email" value="<?php echo base64_decode($row[4]); ?>" id="email" size="40" class="rounded-sm float-right ml-3 text-secondary">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="photo" id="error-photo">Photo</label>
                        <input type="file" name="photo" value="" id="photo" class="rounded-sm float-right ml-3"><br>
                        <label id="image_errorMsg" class="font-weight-bold" style="font-size: 12px; color: red"></label>
                    </td>
                </tr
                <tr>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="update_userInfo" value="Update" class="btn-warning rounded-lg w-100 float-right rs_btn_size mt-4  ml-3">
                    </td>
                </tr>
            </table>
        </form>
            <?php
        }
        ?>
    </div>
</div>
</body>

