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
    <title>Settings</title>
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
        //Validate change password inputs
       function validateChangePassword(){
           var currentPassword = document.forms["change-password-form"]["c_password"].value;
           var newPassword = document.forms["change-password-form"]["n_password"].value;
           var confirmPassword = document.forms["change-password-form"]["conf_password"].value;
           var error_flag = false;

           //current password
           if (currentPassword==""){
               document.getElementById('error-currentPassword').style.color = "red";
               error_flag = true;
           }else {
               document.getElementById('error-currentPassword').style.color = "";
           }
           //new password
           if (newPassword==""){
               document.getElementById('error-new-password').style.color = "red";
               error_flag = true;
           }else {
               document.getElementById('error-new-password').style.color = "";
           }
           //confirm password
           if (confirmPassword==""){
               document.getElementById('error-confirmPassword').style.color = "red";
               error_flag = true;
           }else {
               document.getElementById('error-confirmPassword').style.color = "";
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
        <li class="nav-item shadow-sm w-25"><a class="nav-link text-dark nevBackground pl-3" href="settings_userInfo.php">Update User Information</a></li>
        <li class="nav-item shadow-sm w-25"><a class="nav-link active btn-secondary" href="settings_changePassword.php">Change Password</a></li>
        <li class="nav-item shadow-sm w-25"><a class="nav-link text-dark nevBackground pl-3" href="settings_backup.php">Backup</a></li>
    </ul>
    <!--change username and password container-->
    <div class="ml-4 mt-5 ml-5 pl-5">
        <form class="form-group mb-4 ml-5 pl-5" method="post" name="change-password-form" onsubmit="return validateChangePassword()">
            <table>
                <tr>
                    <td class="tc_position">
                        <label for="c_password" id="error-currentPassword">Current Password</label>
                        <input type="password" name="c_password" id="c_password" size="23" tabindex="3" class="rounded-sm float-right ml-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="n_password" id="error-new-password">New Password</label>
                        <input type="password" name="n_password" id="n_password" size="23" tabindex="4" class="rounded-sm float-right ml-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position">
                        <label for="conf_password" id="error-confirmPassword">Confirm Password</label>
                        <input type="password" name="conf_password" id="conf_password" size="23" tabindex="5" class="rounded-sm float-right ml-3"><br>
                        <label id="error-password-matching" class="font-weight-bold" style="font-size: 12px; color: red"></label>
                    </td>
                </tr>
                <tr>
                    <!--password Error message display-->
                    <?php if (isset($_SESSION['passwordUpdateError'])): ?>
                        <div class=" ml-3 mt-3 mr-3 alert alert-success">
                            <?php
                            echo  $_SESSION['passwordUpdateError'];
                            unset($_SESSION['passwordUpdateError']);
                            ?>
                        </div>
                    <?php endif ?>

                    <?php
                    //check Passwords
                    $userId = $_SESSION['userID'];
                    if (isset($_POST['submit-changePassword'])) {
                        $old_password = $_POST['c_password'];
                        $new_password = $_POST['n_password'];
                        $con_password = $_POST['conf_password'];

                        //check old passwords
                        $query = "SELECT * FROM user WHERE  telephone ='$userId'";
                        $listQueryResult = mysqli_query($connector, $query);

                        $chg_pwd1 = mysqli_fetch_array($listQueryResult);
                        $data_pwd = $chg_pwd1['password'];
                        if (base64_decode($data_pwd) == $old_password) {
                            //check new password and confirm password
                            if ($new_password == $con_password) {
                                $updatePassword = "UPDATE user SET `password`='".base64_encode($new_password)."' WHERE telephone = '$userId'";
                                $queryResult = mysqli_query($connector, $updatePassword);
                                $change_pwd_error = 'Your New Password Updated successfully!';
                            } else {
                                $change_pwd_error= 'Your new and Retype Password is not match!';
                            }
                        } else {
                            $change_pwd_error = 'Please Enter Your Correct Password!';
                        }
                        if ($change_pwd_error){
                            echo '
                                  <div>
                                  <div class="alert alert-success w-75">'. $change_pwd_error .'</div>
                                   <div>';
                        }
                    }
                    ?>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit-changePassword" value="Save" tabindex="6" class="btn-info rounded-lg float-right rs_btn_size mt-4 ml-3">
                        <input type="reset" name="reset" value="Reset" tabindex="7" class="btn-success rounded-lg float-right rs_btn_size mt-4 ml-3">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</body>




