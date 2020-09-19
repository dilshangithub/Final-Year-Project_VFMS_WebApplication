<?php
include('databaseConnector.php');

if (isset($_POST['view'])) {
    //Update notify_status in Fuel management and trip schedule table
    if ($_POST["view"] != '') {
        $update_query = "UPDATE fuelmanagement SET notify_status = 1 WHERE notify_status =0";
        mysqli_query($connector, $update_query);
        $update_query1 = "UPDATE tripschedule SET notify_status = 1 WHERE notify_status =0";
        mysqli_query($connector, $update_query1);
    }

    //Get records from fuel management table and records limit to 5
    $fuel_query = "SELECT * FROM fuelmanagement ORDER BY id DESC LIMIT 5";
    $fuel_result = mysqli_query($connector, $fuel_query);
    $output = '';
    $countSet = 0;

    if (mysqli_num_rows($fuel_result) > 0) {
        while ($row = mysqli_fetch_array($fuel_result)) {
            //Fuel Notification
            $output .= '
                        <li  style="padding-left: 5px; width: 300px ; ">
                            <a href="fuelManagement.php" style="color: #16181b; text-decoration: none;">
                                <img src="dashboard_images/fuel_icon.png" style="width: 20px;">
                                <strong style="font-size: 14px; color: midnightblue">Inserted new fuel Record!</strong><br />
                                <small style="color: #4e555b; font-size: 12px"><em>You have new fuel record from vehicle ' . $row["vehicleNo"] . ' at ' . $row["time"] . ' on ' . $row["date"] . '! Total Liters: ' . $row["liters"] . ' , Unit Price: ' . $row["unitPrice"] . ' , Total Cost: Rs.' . $row["total"] . '</em></small>
                            </a>
                            <hr style="margin-top: 2px; margin-bottom: 2px;">
                        </li>
            ';
        }
        //number of notifications
        $countSet = $countSet + 1;
    }

    //Get records from trip schedule table and records limit to 5
    $trip_query = "SELECT  scheduleNo, vehicleNo, fname, lname FROM tripschedule INNER JOIN user on user.telephone = tripschedule.driverNo WHERE status='completed' ORDER BY scheduleNo DESC LIMIT 5";
    $trip_result = mysqli_query($connector, $trip_query);
    if (mysqli_num_rows($trip_result) > 0) {
        while ($row2 = mysqli_fetch_array($trip_result)) {
            //Trip Notification
            $output .= '
                        <li style="padding-left: 5px; width: 300px">
                            <a href="tripSchedule.php" style="color: #16181b; text-decoration: none;">
                                <img src="dashboard_images/Schedule_icon.png" style="width: 20px;">
                                <strong style="font-size: 14px; color:green;">'.$row2["fname"].' completed his trip!</strong><br />
                                <small style="color:darkslategray; font-size: 12px"><em>Driver '.$row2["fname"] .' '. $row2["lname"].' completed his trip by vehicle '.$row2["vehicleNo"].'. The schedule number is '.$row2["scheduleNo"].' </em></small>
                            </a>
                            <hr style="margin-top: 2px; margin-bottom: 2px;">
                        </li>
            ';
        }
        $countSet = $countSet + 1;
    }

    // if  No Notification
    if ($countSet = 0) {
        $output .= '<li style="padding-left: 5px; width: 400px"><a href="#" style="font-size: 14px;">You haven not any Notifications!</a></li>';
    }

    //Get all the records from fuel management table where the notify_status = 0
    $status_query = "SELECT * FROM fuelmanagement WHERE notify_status = 0";
    $result_query = mysqli_query($connector, $status_query);
    $count1 = mysqli_num_rows($result_query);

    //Get all the records from trip schedule table where the notify_status = 0 and status = completed
    $tripstatus_query = "SELECT * FROM tripschedule WHERE notify_status = 0 AND status='completed'";
    $tripresult_query = mysqli_query($connector, $tripstatus_query);
    $count2 = mysqli_num_rows($tripresult_query);
    //Count total records
    $count = $count1 + $count2;
    //Assign notifications and total number of unseen notifications into a array
    $data = array(
        'notification' => $output,
        'unseen_notification' => $count
    );
    //encode the array and pass
    echo json_encode($data);
}