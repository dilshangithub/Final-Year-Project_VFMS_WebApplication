<!--Check login status, session start and include database-->
<?php
//Start session.
session_start();
//if username not assign to the session, this page redirect to the login page.
include_once 'databaseConnector.php';
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
//check user role and provide access to this page
if ((($_SESSION['role'])=="Driver") || (($_SESSION['role'])=="Scheduler") || (($_SESSION['role'])=="Maintainer") ) {
    header('location:accessError.php');
}
?>

<!--insert date to database-->
<?php

//create variables for hold data
$fname ="";
$lname ="";
$address ="";
$nic ="";
$mobilePhone ="";
$hiredDate ="";
$address="";
$email ="";
$empImage ="";
$username ="";
$password ="";
$role = $_POST['user_role'];
$d_licenceNo ="";
$d_licenceExpire ="";
$d_insurance ="";
$d_insuExpire ="";
$d_insuranceNo = "";
//check whether the submit button click or not. if the button is clicked, values are assign to variables.
if (isset($_POST['submit_userInfo'])){
    if (($role=="Admin") || ($role=="Maintainer") || ($role=="Scheduler")){
        $fname = $_POST['f_name'];
        $lname = $_POST['l_name'];
        $address = $_POST['address'];
        $nic = $_POST['nic'];
        $address = $_POST['address'];
        $mobilePhone = $_POST['mobileNo'];
        $hiredDate = $_POST['h_date'];
        $email = $_POST['email'];
        $empImage = addslashes (file_get_contents($_FILES['d_image']['tmp_name']));
        $username = $_POST['d_username'];
        $password = $_POST['d_password'];

        $insertUserInfo = "INSERT INTO `user` (`fname`, `lname`, `telephone`,`address`, `email`, `nic`,`hireDate`, `image`, `username`, `password`, `role`)VALUES ('$fname', '$lname', '$mobilePhone','".base64_encode($address)."', '".base64_encode($email)."', '".base64_encode($nic)."','$hiredDate', '$empImage', '$username', '".base64_encode($password)."','$role')";

        $insertUserInfoQueryResult = mysqli_query($connector, $insertUserInfo) or die (mysqli_error($connector));
        //display insert success message. the message assign to session variable.
        $_SESSION['userInfoInsertSuccessfull'] = 'Record inserted successfully!';
        //the page redirect to fuel management page.
        header('location: userRegistration.php');
    }
    elseif (($role=="Driver")){
        $fname = $_POST['f_name'];
        $lname = $_POST['l_name'];
        $address = $_POST['address'];
        $nic = $_POST['nic'];
        $mobilePhone = $_POST['mobileNo'];
        $hiredDate = $_POST['h_date'];
        $email = $_POST['email'];
        $empImage = addslashes (file_get_contents($_FILES['d_image']['tmp_name']));
        $username = $_POST['d_username'];
        $password = $_POST['d_password'];
        $d_licenceNo = $_POST['d_licence'];
        $d_licenceExpire = $_POST['licence_expDate'];
        $d_insurance = $_POST['d_insuranceCet'];
        $d_insuExpire = $_POST['insuranceExDate'];
        $d_insuranceNo = $_POST['insuranceNo'];
        //for users
        $insertUserInfo = "INSERT INTO `user` (`fname`, `lname`, `telephone`,`address`, `email`, `nic`,`hireDate`, `image`, `username`, `password`, `role`)VALUES ('$fname', '$lname', '$mobilePhone','".base64_encode($address)."', '".base64_encode($email)."', '".base64_encode($nic)."','$hiredDate', '$empImage', '$username', '".base64_encode($password)."','$role')";
        $insertUserInfoQueryResult = mysqli_query($connector, $insertUserInfo) or die (mysqli_error($connector));
        //for driver
        $insertDriverInfo = "INSERT INTO `driver` (`driverNo`, `licenseNo`, `lexpireDate`, `insurance`, `insuranceNo`,`iexpireDate`)VALUES ('$mobilePhone', '$d_licenceNo', '$d_licenceExpire', '$d_insurance', '$d_insuranceNo','$d_insuExpire')";
        $insertDriverInfoQueryResult = mysqli_query($connector, $insertDriverInfo) or die (mysqli_error($connector));
        //display insert success message. the message assign to session variable.
        $_SESSION['userInfoInsertSuccessfull'] = 'Record inserted successfully!';
        //the page redirect to fuel management page.
        header('location: userRegistration.php');
    }
}
?>

<!--Update user information-->
<!--Update page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Employee Information</title>
    <script src="jquery-3.3.1.min.js"></script>
    <script src="popper.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>
        //Function Visible password
        function passwordVisible() {
            var password = document.getElementById("d_password");
            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }
    </script>
</head>
<body>
<!--Upper information bar-->
<div style="background-color: black;" class="w-100 float-right p-2 fixed-top">
    <!--Display Last login time-->
    <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-5 float-right">
        Last Login Time : <?php echo "  ". $_SESSION['lastLogin'];?>
    </div>
    <!--Display user role-->
    <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-4 float-right">
        User Role : <?php echo "  ". $_SESSION['role'];?>
    </div>
    <!--Display user name-->
    <div style="color: #dbdbdb;" class=" mt-3 ml-3 mr-4 float-right">
        <?php echo "  ". $_SESSION['firstName'] ." ".$_SESSION['lastName'];?>
    </div>
</div>

<h2 style="margin-top: 100px" class="text-dark text-center">Update Employee Information of <b> <?php echo $_GET['fname'];?> <?php echo $_GET['lname'];?></b></h2>
<!--info update container-->
<div  class="container  w-75 mt-4 rounded-lg pb-2">
    <?php
    //get id of update recode
    $updateId = $_GET['updateId'];
    if(isset($_POST['updateUserInfo'])) {
        $fname = $_POST['f_name'];
        $lname = $_POST['l_name'];
        $address = $_POST['address'];
        $nic = $_POST['nic'];
        $mobilePhone = $_POST['mobileNo'];
        $hiredDate = $_POST['h_date'];
        $email = $_POST['email'];
        $username = $_POST['d_username'];
        $password = $_POST['d_password'];
        $role = $_POST['user_role'];

        $updateQuery1 = "UPDATE `user` SET `fname`='$fname',`lname`='$lname',`address`='".base64_encode($address)."',`email`='".base64_encode($email)."',`nic`='".base64_encode($nic)."',`hireDate`='$hiredDate',`username`='$username',`password`='".base64_encode($password)."',`role`='$role' WHERE telephone = '$mobilePhone'";
        $queryResult1 = mysqli_query($connector, $updateQuery1) or die();
        //display insert success message. the message assign to session variable.
        $_SESSION['userInfoUpdateSuccessfull'] = 'Record inserted successfully!';
        //the page redirect to user management page.
        header('location: userRegistration.php');
    }
    //if user click close window button, then redirect update page into user registration page.
    if (isset($_POST['closeWindow'])){
        header('location: userRegistration.php');
    }

    //get current values from database
    $selectQuery = "SELECT * FROM user WHERE telephone = '$updateId'";
    $queryResult = mysqli_query($connector, $selectQuery);
    while ($row = mysqli_fetch_row($queryResult)) {
        ?>
        <!--display current values on form-->
        <form method="post" action="" enctype="multipart/form-data">
            <table class="w-100 mt-5">
                <tr>
                    <td class="tc_position2">
                        <label for="f_name" id="error-f-name">First Name</label>
                        <input type="text" name="f_name" id="f_name" size="27" value="<?php echo $row[0]; ?>" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                    <td class="tc_position2">
                        <label for="l_name" id="error-l-name">Last Name</label>
                        <input type="text" name="l_name" id="l_name" size="27" value="<?php echo $row[1]; ?>" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position2">
                        <label for="address" id="error-address">Address</label>
                        <input type="text" name="address" id="address" size="27" value="<?php echo base64_decode($row[3]); ?>" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                    <td class="tc_position2">
                        <label for="nic" id="error-nic">NIC Number</label>
                        <input type="text" name="nic" id="nic" size="27" value="<?php echo base64_decode($row[5]); ?>" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position2">
                        <label for="mobileNo" id="error-mobileNo">Mobile Phone</label>
                        <input type="text" name="mobileNo" id="mobileNo" value="<?php echo $row[2]; ?>" readonly size="27" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                    <td class="tc_position2">
                        <label for="h_date" id="error-h-date">Hired Date</label>
                        <input type="date" name="h_date" id="h_date" size="27" value="<?php echo $row[6]; ?>" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position2">
                        <label for="email" id="error-email">Email Address</label>
                        <input type="text" name="email" id="email" size="27" value="<?php echo base64_decode($row[4]); ?>" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position2">
                        <label for="d_username" id="error-d-username">Username</label>
                        <input type="text" name="d_username" id="d_username" size="27" value="<?php echo $row[8]; ?>" class="rounded-lg float-right ml-2 mr-3">
                    </td>
                </tr>
                <tr>
                    <td class="tc_position2">
                        <label for="d_password" id="error-d-password">Password</label>
                        <input type="password" name="d_password" id="d_password" size="27" value="<?php echo base64_decode($row[9]); ?>" class="rounded-lg float-right ml-2 mr-3"> <br>
                    </td>
                    <td class="tc_position2">
                        <label for="user_role" id="error-role">Select Role</label>
                        <select name="user_role" onclick="hideElements()" id="user_role" class="rounded-lg float-right mr-3">
                            <option ><?php echo $row[10]; ?></option>
                            <option value="Admin">Admin</option>
                            <option value="Maintainer">Maintainer</option>
                            <option value="Scheduler">Scheduler</option>
                        </select>
                        <label id="role-message"></label>
                    </td>
                </tr>
                <tr>
                    <td class="float-right">
                        <div class="mt-2 mr-3">Visible Password  <input type="checkbox" onclick="passwordVisible()"></div>
                    </td>
                </tr>

                <tr>
                    <td colspan="3">
                        <!--Update button-->
                        <input type="submit" name="updateUserInfo" value="Update Information" class="btn-warning mt-5 rounded-lg w-100 rs_btn_size">
                        <!--close window button-->
                        <input type="submit" name="closeWindow" value="Close Update Window" class="btn-danger mt-2 rounded-lg w-100 rs_btn_size">
                    </td>
                </tr>
            </table>
        </form>
        <?php
    }
    ?>
</div>
</body>
</html>

<!--Delete user information record-->
<?php
//check whether the delete recode id is available or not
if (isset($_GET['deleteId'])){
    //assign delete id into variable
    $id = $_GET['deleteId'];
    //delete query
    $deleteQuery = "DELETE from `user` WHERE `telephone` = '$id'";
    $deleteQueryResult = mysqli_query($connector, $deleteQuery)or die();
}
?>
