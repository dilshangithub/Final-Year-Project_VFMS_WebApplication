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
    <title>Licence Update Management</title>
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
        //validate licence management inputs
        function validatelicenceManagementForm() {
            var date = document.forms["licenceManagement-form"]["date"].value;
            var vehicleNo = document.forms["licenceManagement-form"]["v_no"].value;
            var licenceNo = document.forms["licenceManagement-form"]["RL_no"].value;
            var licencedDate = document.forms["licenceManagement-form"]["l_date"].value;
            var expiredDate = document.forms["licenceManagement-form"]["ex_date"].value;
            var licenceFee = document.forms["licenceManagement-form"]["RL_fee"].value;
            var VETC = document.forms["licenceManagement-form"]["VETC"].value;
            var VETCFee = document.forms["licenceManagement-form"]["VETC_fee"].value;
            var error_flag = false;

            //vehicle No
            if(vehicleNo == "Vehicle_No"){
                document.getElementById('error-vehicleNo').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-vehicleNoService').style.color = "";
            }
            //date
            if(date==''){
                document.getElementById('error-date').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-date').style.color = "";
            }
            //licence No
            if(licenceNo==''){
                document.getElementById('error-licenceNo').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-licenceNo').style.color = "";
            }
            //licenced date
            if(licencedDate==''){
                document.getElementById('error-licencedDate').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-licencedDate').style.color = "";
            }
            //expired date
            if(expiredDate==''){
                document.getElementById('error-expiredDate').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-expiredDate').style.color = "";
            }
            //licence fee
            if (/^\d{1,6}\.\d{0,2}$/.test(licenceFee)){
                document.getElementById('error-licenceFee').style.color = "";
            }else {
                document.getElementById('error-licenceFee').style.color = "red";
                error_flag = true;
            }
            //VETC
            if(VETC==''){
                document.getElementById('error-VETC').style.color = "red";
                error_flag = true;
            }else {
                document.getElementById('error-VETC').style.color = "";
            }
            //VETC fee
            if (/^\d{1,6}\.\d{0,2}$/.test(VETCFee)){
                document.getElementById('error-VETCFee').style.color = "";
            }else {
                document.getElementById('error-VETCFee').style.color = "red";
                error_flag = true;
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
<div class="vm_upperNavContainer border border-secondary nevBackground rounded-lg shadow-sm">
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
            <div class="vm_upperNavHide">
                <img src="dashboard_images/spareparts_icon.png" alt="sparepartsIcon" class="vm_upperNavIcon">
                <p class=" vm_icon_fontSize"><b>Service & Spare Parts</b></p>
            </div>
        </a>

        <!--Licence Management-->
        <a class="nav-link text-center vm_upperNavPosition" href="licenceUpdateManagement.php">
            <div class="">
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
<!--Licence Update management body-->
<div class="container-fluid fm_form1_container rounded-lg shadow-sm pb-2">
    <form method="post" action="licenceUpdateServer.php" name="licenceManagement-form" onsubmit="return validatelicenceManagementForm()">
        <label class="text-dark"><b>Add Licence Information</b></label>

        <!--licence recode inserted successfully message display-->
        <?php if (isset($_SESSION['licenceInfoInsertSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Inserted!',
                    'Record inserted successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['licenceInfoInsertSuccessfull']);
            ?>
        <?php endif ?>
        <table class="w-100">
            <tr>
                <td class="tc_position">
                    <label for="date" id="error-date">Date</label>
                    <input type="date" name="date" id="date" class="rounded-lg float-right ml-2">
                </td>
                <td class="tc_position">
                    <label for="v_no" id="error-vehicleNo">Vehicle Number</label>
                    <select name="vehicleNo" id="v_no" class="rounded-lg float-right ml-2 license_input_size">
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
                    <label for="RL_no" id="error-licenceNo">License No</label>
                    <input type="text" name="RL_no" id="RL_no" size="11" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position">
                    <label for="l_date" id="error-licencedDate">Licensed Date</label>
                    <input type="date" name="l_date" id="l_date" class="rounded-lg float-right ml-2">
                </td>
                <td class="tc_position">
                    <label for="ex_date" id="error-expiredDate">Expired Date</label>
                    <input type="date" name="ex_date" id="ex_date" class="rounded-lg float-right ml-2">
                </td>
                <td class="tc_position">
                    <label for="RL_fee" id="error-licenceFee">License Fee</label>
                    <input type="text" name="RL_fee" id="RL_fee" size="" placeholder="1500.00" class="rounded-lg float-right ml-2 mr-3">
                </td>
            </tr>
            <tr>
                <td class="tc_position">
                    <label for="VETC" id="error-VETC">VET Certificate</label>
                    <input type="text" name="VETC" id="VETC" size="11" class="rounded-lg float-right ml-2">
                    <b><label class="font-weight-bold font-italic VETC_font_size">Vehicle Estimation Test</label></b>
                </td>
                <td class="tc_position">
                    <label for="VETC_fee" id="error-VETCFee">VET Certificate Fee</label>
                    <input type="text" name="VETC_fee" id="VETC_fee" size="11" placeholder="1500.00" class="rounded-lg float-right ml-2">
                    <b> <label class="font-weight-bold font-italic VETC_font_size">Vehicle Estimation Test</label></b>
                </td>
                <td>
                    <div class="">
                        <input type="submit" name="submitLicenceInfo" value="Submit" class="btn-info rounded-lg float-right rs_btn_size ml-4 mr-3">
                        <input type="reset" name="reset" value="Reset" class="btn-success rounded-lg float-right rs_btn_size ">
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<!--Licence update history section-->
<!--history container-->
<div class="container-fluid fm_form1_container rounded-lg shadow-sm pt-2 mb-4">
    <!--search-->
    <form method="post" action="" class="pb-4">
        <label class="text-dark  pb-2"><b>Licence Information History</b></label><br>

        <!--licence recode update successfully message display-->
        <?php if (isset($_SESSION['licenceInfoUpdateSuccessfull'])): ?>
            <script>
                Swal.fire(
                    'Updated!',
                    'Record Updated successfully!',
                    'success'
                )
            </script>
            <?php
            unset($_SESSION['licenceInfoUpdateSuccessfull']);
            ?>
        <?php endif ?>

        <table>
            <tr>
                <td class="tc_position2">
                    <select name="searchVehicleNo" id="v_no" class="rounded-lg license_input_size">
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
        $query = "SELECT * FROM `licencemanagement` WHERE vehicleNo='$searchVal' ORDER BY licencedDate DESC ";
        $listQueryResult = mysqli_query($connector,$query);

        if (mysqli_num_rows($listQueryResult)>0){
            //showing data inside a table
            echo '<table class="table border shadow-sm">';
            echo '<tr class="thead-dark">';
            //display table columns
            echo '<th class="historyTableTxt text-center">Date</th>';
            echo '<th class="historyTableTxt text-center">Vehicle No</th>';
            echo '<th class="historyTableTxt text-center">License No</th>';
            echo '<th class="historyTableTxt text-center">Licensed Date</th>';
            echo '<th class="historyTableTxt text-center">Expired Date</th>';
            echo '<th class="historyTableTxt text-center">License Fee</th>';
            echo '<th class="historyTableTxt text-center">VET Certificate</th>';
            echo '<th class="historyTableTxt text-center">VET Fee</th>';
            echo '<th class="historyTableTxt text-center"></th>';
            echo '</tr>';
            // output data of each row
            //Creates a loop to loop through results
            while ($row= mysqli_fetch_assoc($listQueryResult)){
                echo "<tr id='$row[id]'>
                            <td class='text historyTableTxt text-center'>" . $row['date'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['licenceNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['licencedDate'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['expireDate'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['licenceFee'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['VETcertificate'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['VETcertificateFee'] . "</td>
                            <td class='text historyTableTxt text-center'>
                            <button name='delete' value='D' class='btn btn-danger float-right ml-2 historyTableTxt deleteRecord'>D</button></a>
                             <a href='licenceUpdateServer.php?updateId=$row[id] && vehicleNo=$row[vehicleNo]'><input type='submit' name='updateFuelInfo' value='U' class='btn btn-warning float-right historyTableTxt'>   
                            </td></a>
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
                        url: 'licenceUpdateServer.php',
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
                        'License record has been deleted.',
                        'success'
                    )
                }
            })
        }
    );
</script>
