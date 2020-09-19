<?php
//Start session.
session_start();
//if username not assign to the session, this page redirect to the login page.
if (!isset($_SESSION['username'])){
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Access Error</title>
    <script src="jquery-3.3.1.min.js"></script>
    <script src="popper.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>

</head>
<body>
<div class="text-center">
    <!--Display access error-->
    <p style="font-size: 100px" class="font-weight-bold text-danger mt-5">SORRY!</p>
    <p style="font-size: 30px">You do not have access to this feature...</p>
    <!--if the button is clicked, the page redirect to the dashboard-->
    <a href="dashboard.php"><input type="button" value="Back to Dashboard" class="btn btn-warning mt-5"></a>
</div>
</body>
</html>