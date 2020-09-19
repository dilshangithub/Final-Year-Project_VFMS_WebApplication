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
    <title>Vehicle Registration</title>
    <!--link proper js-->
    <script src="popper.js"></script>
    <!--link sweetAlert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--link jquery-->
    <script src="jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="extraCss.css">

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
        //validate vehicle info inputs
        function validateVehicleRegistrationForm() {
            var regNo = document.forms["vehicle-reg-form"]["reg_no"].value;
            var vehicleImage = document.forms["vehicle-reg-form"]["v_image"].value;
            var type = document.forms["vehicle-reg-form"]["v_type"].value;
            var modelYer = document.forms["vehicle-reg-form"]["model_year"].value;
            var chassisNo = document.forms["vehicle-reg-form"]["chass_no"].value;
            var make = document.forms["vehicle-reg-form"]["make"].value;
            var engineNo = document.forms["vehicle-reg-form"]["e_no"].value;
            var color = document.forms["vehicle-reg-form"]["v_color"].value;
            var transmission = document.forms["vehicle-reg-form"]["transmission"].value;
            var perchesdate = document.forms["vehicle-reg-form"]["perch_date"].value;
            var noOfTires = document.forms["vehicle-reg-form"]["noOfTires"].value;
            var capacity = document.forms["vehicle-reg-form"]["v_capacity"].value;
            var containerType = document.forms["vehicle-reg-form"]["cont_type"].value;
            var error_flag = false;

            //validate regNo
            if(regNo==''){
                document.getElementById('error-reg-no').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-reg-no').style.color = "";
            }
            //validate image
            if(vehicleImage == ''){
                document.getElementById('error-v-image').style.color = "red";
                error_flag = true;
            }else{
                var extension = vehicleImage.substring(vehicleImage.lastIndexOf('.')+1).toLowerCase();
                if(extension == "gif"||extension == "png"||extension == "bmp"||extension == "jpeg"||extension == "jpg"){
                    document.getElementById('error-v-image').style.color = "";
                }else {
                    document.getElementById('image_errorMsg').innerHTML='File Formats: GIF/PNG/BMP/JPEG/JPG';
                    error_flag = true;
                }
            }
            //validate vehicle type
            if (type == ""){
                document.getElementById('error-v-type').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-v-type').style.color = "";
            }
            //validate vehicle model year
            if (/^[[0-9]{4}$/.test(modelYer)){
                document.getElementById('error--model-year').style.color = "";
            }else {
                document.getElementById('error--model-year').style.color = "red";
                error_flag = true;
            }
            //validate vehicle chassis no
            if (chassisNo == ""){
                document.getElementById('error-chassis-no').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-chassis-no').style.color = "";
            }
            //validate vehicle make
            if (/^[a-zA-Z]+$/.test(make)){
                document.getElementById('error-make').style.color = "";
            }else {
                document.getElementById('error-make').style.color = "red";
                error_flag = true;
            }
            //validate vehicle engine no
            if (engineNo == ""){
                document.getElementById('error-engineNo').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-engineNo').style.color = "";
            }
            //validate vehicle color
            if (/^[a-zA-Z]+$/.test(color)){
                document.getElementById('error-vehicle-color').style.color = "";
            }else {
                document.getElementById('error-vehicle-color').style.color = "red";
                error_flag = true;
            }
            //validate vehicle transmission
            if (transmission == ""){
                document.getElementById('error-transmission').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-transmission').style.color = "";
            }
            //validate vehicle perches date
            if (perchesdate == ""){
                document.getElementById('error-perches-date').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-perches-date').style.color = "";
            }
            //validate vehicle no of tiers
            if (/^[[0-9]+$/.test(noOfTires)){
                document.getElementById('error-no-Of-tires').style.color = "";

            }else {
                document.getElementById('error-no-Of-tires').style.color = "red";
                error_flag = true;
            }
            //validate vehicle capacity
            if (capacity==''){
                document.getElementById('error-v-capacity').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-v-capacity').style.color = "";
            }
            //validate vehicle container type
            if (/^[a-zA-Z]+$/.test(containerType)){
                document.getElementById('error-containerType').style.color = "";
            }else {
                document.getElementById('error-containerType').style.color = "red";
                error_flag = true;
            }
            if (error_flag){
                return false;
            }else{
                return true;
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
                        <a class="nav-link fontColor  sidebarIconHide" href="dashboard.php">
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
                        <a class="nav-link fontColor sideNavActive" href="vehicleRegistration.php">
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

<!--Vehicle Registration body-->
<div class="container-fluid fm_form2_container rounded-lg shadow-sm pb-2">
    <a href="mobileReg.php" target="_blank"><input type="button" value="Allocate Mobile Phone" style="background-color: black" class="text-light rounded-lg mt-1 mb-4 p-2"></a>

    <form method="post" action="vehicleRegistrationServer.php" enctype="multipart/form-data" name="vehicle-reg-form" onsubmit="return validateVehicleRegistrationForm()">

        <!--vehicle recode inserted successfully message display-->
        <?php if (isset($_SESSION['vehicleInfoInsertSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Inserted!',
                    'Vehicle record inserted successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['vehicleInfoInsertSuccessfull']);
            ?>
        <?php endif ?>
        <table class="w-100">
            <tr>
                <td><label class="text-dark"><h5><b>Basic Vehicle Information</b></h5></label></td>
                <td>
                    <label class="text-dark"><h5><b>Mechanical Information</b></h5></label>
                </td>

            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="reg_no" id="error-reg-no">Registration Number</label>
                    <input type="text" name="reg_no" id="reg_no" size="20" placeholder="Eg: NB2345" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2" rowspan="2">
                    <label for="v_image" id="error-v-image">Vehicle Image</label>
                    <input type="file" name="v_image" id="v_image" class="form-control-file rounded-lg float-right pl-3 mr-3"><br>
                    <label id="image_errorMsg" class="font-weight-bold" style="font-size: 12px; color: red"></label>
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="v_type" id="error-v-type">Vehicle Type</label>
                    <input type="text" name="v_type" id="v_type" placeholder="Eg: Mini truck" size="20" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="model_year" id="error--model-year">Model Year</label>
                    <input type="text" name="model_year" id="model_year" placeholder="Eg: 2019" size="20" class="rounded-lg float-right ml-2  mr-3">
                </td>
                <td class="tc_position2">
                    <label for="chass_no" id="error-chassis-no">Chassis Number</label>
                    <input type="text" name="chass_no" id="chass_no" placeholder="Eg: 1HGBH41JXMN109186" size="20" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="make" id="error-make">Make</label>
                    <input type="text" name="make" id="make" size="20" placeholder="Eg: Toyota" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="e_no" id="error-engineNo">Engine Number</label>
                    <input type="text" name="e_no" id="e_no" placeholder="Eg: 52WVC10338" size="20" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="v_color" id="error-vehicle-color"> Vehicle Color</label>
                    <input type="text" name="v_color" id="v_color" size="20" placeholder="Eg: White" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2">
                    <label for="transmission" id="error-transmission">Transmission</label>
                    <select name="transmission" id="transmission" class="rounded-lg float-right ml-2 mr-3 vehicleReg_input_size">
                        <option>Manual</option>
                        <option>Automatic</option>
                        <option>Automated Manual</option>
                        <option>Continuously Variable</option>
                        <option>Dual-Clutch</option>
                        <option>Tiptronic</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="perch_date" id="error-perches-date">Perches Date</label>
                    <input type="date" name="perch_date" id="perch_date" class="rounded-lg float-right ml-2 mr-3 vehicleReg_input_size">
                </td>
                <td class="tc_position2">
                    <label for="noOfTires" id="error-no-Of-tires">Number of Tyres</label>
                    <input type="text" name="noOfTires" id="noOfTires" placeholder="Eg: 6" size="20" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="v_capacity" id="error-v-capacity">Vehicle Capacity</label>
                    <input type="text" name="v_capacity" id="v_capacity" placeholder="Eg: 1000Kg" size="20" class="rounded-lg float-right ml-2 mr-3">
                </td>
                <td class="tc_position2" rowspan="2">
                    <div class="">
                        <input type="submit" name="submitVehicleInfo" value="Submit" class="btn-info rounded-lg float-right rs_btn_size mt-4 ml-4 mr-3">
                        <input type="reset" name="reset"  value="Reset" class="btn-success rounded-lg float-right rs_btn_size mt-4 mr-3">
                    </div>
                </td>
            </tr>
            <tr>
                <td class="tc_position2">
                    <label for="cont_type" id="error-containerType">Container Type</label>
                    <input type="text" name="cont_type" id="cont_type" placeholder="Eg: Freezer" size="20" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
        </table>
    </form>
</div>

<!--Vehicle search and delete section-->
<!--search container-->
<div class="container-fluid fm_form1_container rounded-lg shadow-sm pt-2 mb-4">
    <!--search-->
    <form method="get" action="" class="pb-4">
        <label class="text-dark  pb-2"><b>Search Vehicles</b></label><br>
        <!--vehicle recode update successfully message display-->
        <?php if (isset($_SESSION['vehicleInfoUpdateSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Updated!',
                    'Vehicle record Updated successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['vehicleInfoUpdateSuccessfull']);
            ?>
        <?php endif ?>

        <table>
            <tr>
                <td class="tc_position2">

                    <select name="search" class="rounded-lg">
                        <?php
                        $selectQuery = "SELECT `regNo` FROM `vehicle`";
                        $queryResult = mysqli_query($connector, $selectQuery);
                        echo '<option>Vehicle No</option>';
                        while ($row = mysqli_fetch_assoc($queryResult)){
                            echo '<option value="'. $row['regNo'] . '">'
                                . $row['regNo'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
                <td class="tc_position2">
                    <input type="submit" name="searchVehicle" value="Search" class="rounded-lg btn-info ml-2 rs_btn_size">
                </td>
            </tr>
        </table>
    </form>
    <!--vehicle info table-->

    <?php
    //delete vehicle
    if (isset($_GET['deleteVehicle'])) {
        $searchVal = $_GET['search'];
        if ($searchVal != 'Vehicle No') {
            $deleteQuery = "DELETE from `vehicle` WHERE `regNo` = '$searchVal'";
            $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
            echo '<lable class="text-success ml-3 font-weight-bold">Vehicle recode successfully deleted!</lable>';
        } else {
            echo '<lable class="text-danger ml-3 font-weight-bold">Select Correct Vehicle Number!</lable>';
        }
    }
    ?>

    <?php
    //search vehicle
        if (isset($_GET['searchVehicle'])){
        $searchVal = $_GET['search'];
        $query = "SELECT * FROM `vehicle` WHERE regNo='$searchVal'";
        $listQueryResult = mysqli_query($connector,$query);

        if (mysqli_num_rows($listQueryResult)>0){
            //showing data inside a table
            echo '<table class="table border shadow-sm">';
            echo '<tr class="thead-dark">';
            //display table columns
                echo '<th class="historyTableTxt text-center">Vehicle No</th>';
                echo '<th class="historyTableTxt text-center">Vehicle Type</th>';
                echo '<th class="historyTableTxt text-center">Model Year</th>';
                echo '<th class="historyTableTxt text-center">Make</th>';
                echo '<th class="historyTableTxt text-center">Color</th>';
                echo '<th class="historyTableTxt text-center">Perches Date</th>';
                echo '<th class="historyTableTxt text-center">Chassis No</th>';
                echo '<th class="historyTableTxt text-center">Engine No</th>';
                echo '<th class="historyTableTxt text-center">Transmission</th>';
                echo '<th class="historyTableTxt text-center">Capacity</th>';
                echo '<th class="historyTableTxt text-center">Container Type</th>';
                echo '<th class="historyTableTxt text-center">Number of Tyres</th>';
                echo '<th class="historyTableTxt text-center"></th>';
            echo '</tr>';
            // output data of each row
            //Creates a loop to loop through results
            while ($row= mysqli_fetch_assoc($listQueryResult)){
                echo "<tr id='$row[regNo]'>
                    <td class='text historyTableTxt text-center'>" . $row['regNo'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['type'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['modelYear'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['make'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['color'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['perchesDate'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['chassisNo'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['engineNo'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['transmission'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['capacity'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['containerType'] . "</td>
                    <td class='text historyTableTxt text-center'>" . $row['tyres'] . "</td>
                    <td class='text historyTableTxt text-center'>
                        <a href='vehicleRegistrationServer.php?updateId=$row[regNo]'><input type='submit' name='updateFuelInfo' value='U' class='btn btn-warning float-right historyTableTxt'></a>  
                    <button name='delete' value='D' class='btn btn-danger float-right ml-2  mt-1 historyTableTxt deleteRecord'>D</button></a>
                    </td>
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
                        url: 'vehicleRegistrationServer.php',
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
                        'Vehicle record has been deleted.',
                        'success'
                    )
                }
            })
        }
    );
</script>
