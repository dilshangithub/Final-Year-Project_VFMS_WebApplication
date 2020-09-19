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
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Registration</title>
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
        //Function validate inputs
        function validateDriverRegistrationForm(){
            var fName = document.forms["driver-reg-form"]["f_name"].value;
            var lName = document.forms["driver-reg-form"]["l_name"].value;
            var address = document.forms["driver-reg-form"]["address"].value;
            var nic_no = document.forms["driver-reg-form"]["nic"].value;
            var mobile_no = document.forms["driver-reg-form"]["mobileNo"].value;
            var hired_date = document.forms["driver-reg-form"]["h_date"].value;
            var email = document.forms["driver-reg-form"]["email"].value;
            var dImage = document.forms["driver-reg-form"]["d_image"].value;
            var dusername = document.forms["driver-reg-form"]["d_username"].value;
            var dpassword = document.forms["driver-reg-form"]["d_password"].value;
            var userRole = document.forms["driver-reg-form"]["user_role"].value;
            var error_flag = false;

            //first name
            if (/^[a-zA-Z]+$/.test(fName)){
                document.getElementById('error-f-name').style.color = "";
            }else {
                document.getElementById('error-f-name').style.color = "red";
                error_flag = true;
            }
            //last name
            if (/^[a-zA-Z]+$/.test(lName)){
                document.getElementById('error-l-name').style.color = "";
            }else {
                document.getElementById('error-l-name').style.color = "red";
                error_flag = true;
            }
            //address
            if (address==''){
                document.getElementById('error-address').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-address').style.color = "";
            }
            //NIC
            if (nic_no==''){
                document.getElementById('error-nic').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-nic').style.color = "";
            }
            //mobile no
            if (/^[0]{1}[0-9]{9}$/.test(mobile_no)){
                document.getElementById('error-mobileNo').style.color = "";
            }
            else{
                document.getElementById('error-mobileNo').style.color = "red";
                error_flag = true;
            }
            //hired date
            if (hired_date==""){
                document.getElementById('error-h-date').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-h-date').style.color = "";
            }
            //email
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)){
                document.getElementById('error-email').style.color = "";
            }else {
                document.getElementById('error-email').style.color = "red";
                error_flag = true;
            }
            //image
            if(dImage == ''){
                document.getElementById('error-d-image').style.color = "red";
                error_flag = true;
            }else{
                var extension = dImage.substring(dImage.lastIndexOf('.')+1).toLowerCase();
                if(extension == "gif"||extension == "png"||extension == "bmp"||extension == "jpeg"||extension == "jpg"){
                    document.getElementById('error-d-image').style.color = "";
                }else {
                    document.getElementById('image_errorMsg').innerHTML='File Formats: GIF/PNG/BMP/JPEG/JPG';
                    error_flag = true;
                }
            }
            //username
            if (dusername==""){
                document.getElementById('error-d-username').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-d-username').style.color = "";
            }
        //password
        if (dpassword==""){
            document.getElementById('error-d-password').style.color = "red";
            error_flag = true;
        }else {
            document.getElementById('error-d-password').style.color = "";
        }
        //User role
            if (userRole =="Select user role"){
                document.getElementById('error-role').style.color = "red";
                error_flag = true;
            } else {
                document.getElementById('error-role').style.color = "";
            }
            if (error_flag){
                return false;
            }else{
                return true;
            }
        }
        //Hide driver's inputs
        function hideElements() {
            var check = document.getElementById("user_role");
            var result = check.options[check.selectedIndex].value;

            if (result == ("Driver")) {
                document.getElementById("hideRow1").style.display = '';
                document.getElementById("hideRow2").style.display = '';
                document.getElementById("hideRow3").style.display = '';
                document.getElementById("hideRow4").style.display = '';
            } else {
                document.getElementById("hideRow1").style.display = 'none';
                document.getElementById("hideRow2").style.display = 'none';
                document.getElementById("hideRow3").style.display = 'none';
                document.getElementById("hideRow4").style.display = 'none';
            }
        }
    </script>
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
                        <a class="nav-link fontColor sideNavActive" href="userRegistration.php">
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

<!--employee Registration body-->
<div class="container-fluid fm_form2_container rounded-lg shadow-sm pb-2">
    <form method="post" action="userRegistrationServer.php" enctype="multipart/form-data" name="driver-reg-form" onsubmit="return validateDriverRegistrationForm()">
        <!--user recode inserted successfully message display-->
        <?php if (isset($_SESSION['userInfoInsertSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Inserted!',
                    'Record inserted successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['userInfoInsertSuccessfull']);
            ?>
        <?php endif ?>

        <table class="w-100">
            <tr>
                <td colspan="2"><label class="text-dark"><h5><b>Employee Registration</b></h5></label></td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="f_name" id="error-f-name">First Name</label>
                    <input type="text" name="f_name" id="f_name" size="27" placeholder="Eg: Adrew" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="l_name" id="error-l-name">Last Name</label>
                    <input type="text" name="l_name" id="l_name" size="27" placeholder="Eg: Peiris" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="address" id="error-address">Address</label>
                    <input type="text" name="address" id="address" size="27" placeholder="Eg: B2/37, Jawatta Rd, Colombo 07" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="nic" id="error-nic">NIC Number</label>
                    <input type="text" name="nic" id="nic" size="27" placeholder="Eg: *********v" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="mobileNo" id="error-mobileNo">Mobile Phone</label>
                    <input type="text" name="mobileNo" id="mobileNo" size="27" placeholder="Eg: 0713333333" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="h_date" id="error-h-date">Hired Date</label>
                    <input type="date" name="h_date" id="h_date" size="27" class="rounded-lg float-right ml-2 mr-3 userReg_input_size">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="email" id="error-email">Email Address</label>
                    <input type="text" name="email" id="email" size="27" placeholder="Eg: andrewpeiris@gmail.com" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="d_image" id="error-d-image">Employee Image</label>
                    <input type="file" name="d_image" id="d_image" class="form-control-file rounded-lg float-right pl-3 mr-3"><br>
                    <label id="image_errorMsg" class="font-weight-bold" style="font-size: 12px; color: red"></label>
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="d_username" id="error-d-username">Username</label>
                    <input type="text" name="d_username" id="d_username" size="27" placeholder="Eg: andrew123" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="d_password" id="error-d-password">Password</label>
                    <input type="password" name="d_password" id="d_password" size="27" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="user_role" id="error-role">Select Role</label>
                    <select name="user_role" onclick="hideElements()" id="user_role" class="rounded-lg float-right mr-3 userReg_input_size">
                        <option>Select user role</option>
                        <option value="Admin">Admin</option>
                        <option value="Maintainer">Maintainer</option>
                        <option value="Scheduler">Scheduler</option>
                        <option value="Driver">Driver</option>
                    </select>
                    <label id="role-message"></label>
                </td>
            </tr>

            <!--Driver Information-->
            <tr id="hideRow1">
                <td colspan="2"><label class="text-dark mt-2"><h5><b>Driver's Information</b></h5></label></td>
            </tr>
            <tr id="hideRow2">
                <td class="tc_position2">
                    <label for="d_licence" id="error-licenceNo">Driver's Licence No</label>
                    <input type="text" name="d_licence" id="d_licence" size="20" placeholder="Eg: B1234567" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="licence_expDate" id="error-licence-expDate">Driver's Licence Expire Date</label>
                    <input type="date" name="licence_expDate" id="licence_expDate" class="rounded-lg float-right ml-2 mr-3 userReg_input2_size">
                </td>
            </tr>
            <tr id="hideRow3">
                <td class="tc_position2">
                    <label for="d_insuranceCet" id="error-insurance-cet">Driver's Insurance Certificate</label>
                    <input type="text" name="d_insuranceCet" id="d_insuranceCet" size="20" placeholder="Eg: Sri Lanka Insurance" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="insuranceExDate" id="error-insuranceEXdate">Insurance Expire Date</label>
                    <input type="date" name="insuranceExDate" id="insuranceExDate" class="rounded-lg float-right ml-2 mr-3 userReg_input2_size">
                </td>
            </tr>
            <tr id="hideRow4">
                <td class="tc_position2">
                    <label for="insuranceNo" id="error-insuranceNo">Insurance No</label>
                    <input type="text" name="insuranceNo" id="insuranceNo" size="20" placeholder="321455645" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
            <td class="tc_position2" colspan="2">
                <div class="">
                    <input type="submit" name="submit_userInfo" value="Submit" class="btn-info rounded-lg float-right rs_btn_size mt-4 ml-4 mr-3">
                    <input type="reset" name="reset" value="Reset" class="btn-success rounded-lg float-right rs_btn_size mt-4 mr-3">
                </div>
            </td>
            </tr>
        </table>
    </form>
</div>
<!--User search and delete section-->
<!--search container-->
<div class="container-fluid fm_form1_container rounded-lg shadow-sm pt-2 mb-4">
    <!--search-->
    <table class="w-100">
        <tr>
            <td>
    <form method="post" action="" class="pb-4">
        <label class="text-dark  pb-2"><b>Update and Delete Employee Information</b></label><br>

        <!--user recode update successfully message display-->
        <?php if (isset($_SESSION['userInfoUpdateSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Updated!',
                    'User record Updated successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['userInfoUpdateSuccessfull']);
            ?>
        <?php endif ?>

        <table>
            <tr>
                <td class="tc_position2">
                    <select name="searchUserNo" id="user_id" class="rounded-lg">
                        <?php
                        $searchQuery = "SELECT `telephone`,`fname` FROM `user`";
                        $queryResult = mysqli_query($connector, $searchQuery);
                        echo '<option>User Number</option>';
                        while ($row = mysqli_fetch_assoc($queryResult)){
                            echo '<option value="'. $row['telephone'].'">'
                                . $row['telephone'] ." - " .$row['fname'].'</option>';
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
            </td>
            <td>
    <!--Search drivers-->
    <form method="post" action="" class="pb-4">
        <label class="text-dark  pb-2"><b>Update Driver Information</b></label><br>
        <!--driver recode update successfully message display-->
        <?php if (isset($_SESSION['DriverInfoUpdateSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Updated!',
                    'Driver record Updated successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['DriverInfoUpdateSuccessfull']);
            ?>
        <?php endif ?>
        <table>
            <tr>
                <td class="tc_position2">
                    <select name="searchDriverNo" id="user_id" class="rounded-lg">
                        <?php
                        $searchQuery = "SELECT driverNo,fname FROM user INNER JOIN driver ON user.telephone=driver.driverNo";
                        $queryResult = mysqli_query($connector, $searchQuery);
                        echo '<option>Driver Number</option>';
                        while ($row = mysqli_fetch_assoc($queryResult)){
                            echo '<option value="'. $row['driverNo'] . '">'
                                . $row['driverNo']." - ".$row['fname']. '</option>';
                        }
                        ?>
                    </select>
                </td>
                <td class="tc_position2">
                    <input type="submit" name="btn_searchDriver" value="Search" class="rounded-lg btn-info ml-2 rs_btn_size">
                </td>
            </tr>
        </table>
    </form>
            </td>
        </tr>
    </table>

    <!--user table-->
    <?php
    //search user
    if (isset($_POST['btn_search'])) {
        $searchVal = $_POST['searchUserNo'];
        $query = "SELECT * FROM user WHERE telephone = '$searchVal'";
        $listQueryResult = mysqli_query($connector, $query);

        if (mysqli_num_rows($listQueryResult) > 0) {
            //showing data inside a table
            echo '<table class="table border shadow-sm">';
            echo '<tr class="thead-dark">';
            //display table columns
            //Creates a loop to loop through results
            while ($row = mysqli_fetch_assoc($listQueryResult)) {
            echo '<th class="historyTableTxt font-weight-bold text-center">First Name</th>';
            echo '<th class="historyTableTxt font-weight-bold text-center">Last Name</th>';
            echo '<th class="historyTableTxt font-weight-bold text-center">Telephone</th>';
            echo '<th class="historyTableTxt font-weight-bold text-center">Address</th>';
            echo '<th class="historyTableTxt font-weight-bold text-center">Email</th>';
            echo '<th class="historyTableTxt font-weight-bold text-center">NIC No</th>';
            echo '<th class="historyTableTxt font-weight-bold text-center">Hired Date</th>';
            echo '<th class="historyTableTxt font-weight-bold text-center">Role</th>';
            echo '<th class="historyTableTxt font-weight-bold text-center"></th>';
            echo '</tr>';
            echo "<tr id='$row[telephone]'>
                  <td class='text historyTableTxt text-center'>" . $row['fname'] . "</td>
                  <td class='text historyTableTxt text-center'>" . $row['lname'] . "</td>
                  <td class='text historyTableTxt text-center'>" . $row['telephone'] . "</td>
                  <td width='20%' class='text historyTableTxt text-center'>" . base64_decode($row['address']) . "</td>
                  <td  width='20%' class='text historyTableTxt text-center'>" . base64_decode($row['email']) . "</td>
                  <td class='text historyTableTxt text-center'>" . base64_decode($row['nic']). "</td>
                  <td class='text historyTableTxt text-center'>" . $row['hireDate'] . "</td>
                  <td class='text historyTableTxt text-center'>" . $row['role'] . "</td>
                  <td class='text historyTableTxt text-center'>
                  <button name='delete' value='D' class='btn btn-danger float-right ml-2 historyTableTxt deleteRecord'>D</button></a>
                <a href='userRegistrationServer.php?updateId=$row[telephone] && fname=$row[fname] && lname=$row[lname] && role=$row[role]'><input type='submit' name='updateUserlInfo' value='U' class='btn btn-warning float-right historyTableTxt'>   
            </a></td>
                  </tr>";
               }
                echo '</table>';
            }
        else {
                echo '<div class="ml-3 mb-4"><lable class="alert alert-danger"><b>Warning!</b> Cannot find any Records!</lable></div>';
            }
    }
    elseif (isset($_POST['btn_searchDriver'])){
        $searchVal = $_POST['searchDriverNo'];
        $query = "SELECT * FROM driver WHERE driverNo = '$searchVal'";
        $listQueryResult = mysqli_query($connector, $query);

        if (mysqli_num_rows($listQueryResult) > 0) {
            //showing data inside a table
            echo '<table class="table border shadow-sm">';
            echo '<tr class="thead-dark">';
            //display table columns
            //Creates a loop to loop through results
            while ($row = mysqli_fetch_assoc($listQueryResult)) {
                echo '<th class="historyTableTxt font-weight-bold text-center">Driver No</th>';
                echo '<th class="historyTableTxt font-weight-bold text-center">Licence No</th>';
                echo '<th class="historyTableTxt font-weight-bold text-center">Licence Exp Date</th>';
                echo '<th class="historyTableTxt font-weight-bold text-center">Insurance</th>';
                echo '<th class="historyTableTxt font-weight-bold text-center">Insurance No</th>';
                echo '<th class="historyTableTxt font-weight-bold text-center">Insurance Exp Date</th>';
                echo '<th class="historyTableTxt text-center"></th>';
                echo '<tr>';
                echo "<tr>
                        <td class='text historyTableTxt text-center'>" . $row['driverNo'] . "</td>
                        <td class='text historyTableTxt text-center'>" . $row['licenseNo'] . "</td>
                        <td class='text historyTableTxt text-center'>" . $row['lexpireDate'] . "</td>
                        <td class='text historyTableTxt text-center'>" . $row['insurance'] . "</td>
                        <td class='text historyTableTxt text-center'>" . $row['insuranceNo'] . "</td>
                        <td class='text historyTableTxt text-center'>" . $row['iexpireDate'] . "</td>
                        <td class='text historyTableTxt text-center'>
                                     <a href='updateDriver.php?updateId=$row[driverNo]'><input type='submit' name='updateDriverlInfo' value='U' class='btn btn-warning float-right historyTableTxt'>
        </a></td>
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
                        url: 'userRegistrationServer.php',
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
                        'User record has been deleted.',
                        'success'
                    )
                }
            })
        }
    );
</script>