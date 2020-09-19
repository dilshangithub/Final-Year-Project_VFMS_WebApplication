<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <script src="jquery-3.3.1.min.js"></script>
    <script src="popper.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="additionalCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <script>
        //Function Visible password
        function passwordVisible() {
            var password = document.getElementById("password");
            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }
        //Function validate inputs
        function validateInputFields() {
            var username = document.forms["loginForm"]["username"].value;
            var password = document.forms["loginForm"]["password"].value;
            var error_flag = false;

            if (username == ""){
                document.getElementById('usernameError').innerHTML='Enter your correct username';
                error_flag = true;
            }else {
                document.getElementById('usernameError').innerHTML='';
            }
            if (password == ""){
                document.getElementById('passwordError').innerHTML='Enter your correct password';
                error_flag = true;
            }else {
                document.getElementById('passwordError').innerHTML='';
            }
            if (error_flag){
                return false;
            }else{
                return true;
            }
        }
    </script>

</head>
<body style="background-color: black">
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4 ">
            <?php
            //Start session.
            session_start();
            require_once 'databaseConnector.php';

            if (isset($_POST['login'])) {
                $username = $_POST['username'];
                $password = base64_encode($_POST['password']);

                //get username and password from database
                $query = "SELECT * FROM `user` WHERE username = '$username' && password ='$password'";
                $checkUser = mysqli_query($connector, $query);
                $row = mysqli_num_rows($checkUser);
                if ($row == 1) {
                    //assign user's important data into session variables.
                    $_SESSION['username'] = $username;
                    header('location:dashboard.php');
                    ($row = mysqli_fetch_row($checkUser));
                    $_SESSION['firstName'] = $row[0];
                    $_SESSION['lastName'] = $row[1];
                    $_SESSION['role'] = $row[10];
                    $_SESSION['userID'] = $row[2];
                    $_SESSION['password'] = $row[9];

                    //update last login date and time
                   $updateQuery = "UPDATE `user` SET `lastLogin` = NOW() WHERE `telephone` = {$_SESSION['userID']} LIMIT 1";
                    $resultUpdateLastLogin = mysqli_query($connector,$updateQuery);
                    //get last login date and time
                    $_SESSION['lastLogin'] = $row[11];

                } else {
                    echo '<div class="text-white mt-1 ml-4"><lable><b>Login Failed!</b> Check Your Username and Password..</lable></div>';
                }
            }
            ?>

            <div class="contentLogin">
                <!--Logo-->
                <div class="text-center">
                    <img src="dashboard_images/logo2.png" class="w-75 mt-3">
                </div>
                <br>
                <!--software description text-->
                <p class="text-white text-center">V e h i c l e &nbsp; F l e e t &nbsp; M a n a g e m e n t &nbsp; S y s t e m</p>
                <!--Login form-->
                <form action="" method="post" class="bg-secondary text-white mt-4 p-4 rounded-sm shadow-lg" name="loginForm" onsubmit="return validateInputFields()">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                        <label style="color: black"  id="usernameError"></label>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <label style="color: black" id="passwordError"></label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" onclick="passwordVisible()"class="form-check-input">
                        <label class="form-check-label">Show Password</label>
                    </div>
                    <div class="text-center">
                        <input type="submit" value="Login" name="login" style="background-color: black; color: white" class="btn w-50">
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-4"></div>
    </div>

</div>
</body>
</html>