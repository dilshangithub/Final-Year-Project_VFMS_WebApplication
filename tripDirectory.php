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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Trip Directory</title>
    <!--link jquery-->
    <script src="jquery-3.3.1.min.js"></script>
    <!--link proper js-->
    <script src="popper.js"></script>
    <!--link bootstrap style sheet file-->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!--link css file 1-->
    <link rel="stylesheet" href="extraCss.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <script>
        function closeWindow() {
            if (confirm("Close window?")){
                close();
            }
        }
    </script>
</head>
<body>
<div style="background-color: black;" class="w-100 float-right p-2 fixed-top">
    <button class="btn btn-danger float-right mt-1 mr-5" onclick="closeWindow()">Close Window</button>
    <h2 class="text-white ml-3">Trip Schedule Directory</h2>
</div>
<br><br>

<div class="mt-5">
<?php
//search schedule
    $query_search = "SELECT * FROM `tripschedule` ORDER BY `scheduleNo` DESC";
    $listQueryResult = mysqli_query($connector,$query_search);

    if (mysqli_num_rows($listQueryResult)>0){
        //showing data inside a table
        echo '<table class="table table-hover border shadow-sm">';
        echo '<tr class="thead-dark">';
        //display table columns
        echo '<th class="historyTableTxt text-center">Schedule No</th>';
        echo '<th class="historyTableTxt text-center">Scheduled Date</th>';
        echo '<th class="historyTableTxt text-center">Scheduled Time</th>';
        echo '<th class="historyTableTxt text-center">Vehicle No</th>';
        echo '<th class="historyTableTxt text-center">Driver No</th>';
        echo '<th class="historyTableTxt text-center">From</th>';
        echo '<th class="historyTableTxt text-center">To</th>';
        echo '<th class="historyTableTxt text-center">Description</th>';
        echo '<th class="historyTableTxt text-center">Arrival Time</th>';
        echo '<th class="historyTableTxt text-center">Departure Time</th>';
        echo '<th class="historyTableTxt text-center">Status</th>';
        echo '<th class="historyTableTxt text-center">Price</th>';
        echo '</tr>';
        // output data of each row
        //Creates a loop to loop through results
        while ($row= mysqli_fetch_assoc($listQueryResult)){
            echo "<tr>
                            <td class='text historyTableTxt text-center'>" . $row['scheduleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['sdate'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['stime'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['vehicleNo'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['driverNo'] . "</td>
                            <td width='15%' class='text historyTableTxt text-center'>" . $row['start'] . "</td>
                            <td width='15%' class='text historyTableTxt text-center'>" . $row['destination'] . "</td>
                            <td width='15%' class='text historyTableTxt text-center'>" . $row['description'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['arrTime'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['deptTime'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['status'] . "</td>
                            <td class='text historyTableTxt text-center'>" . $row['totalPrice'] . "</td> 
                    </tr>";
        }
        echo '</table>';
    }
    else {
        echo '<div class="ml-3 mb-4"><lable class="alert alert-danger"><b>Warning!</b> Directory is empty.</lable></div>';
    }
?>
</div>
</body>
</html>
