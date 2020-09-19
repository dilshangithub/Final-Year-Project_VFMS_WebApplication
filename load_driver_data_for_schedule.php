<?php
require_once 'databaseConnector.php';
error_reporting(0);

if(isset($_POST["driverNum"])) {
    $sql = "SELECT * FROM user WHERE telephone = '".$_POST["driverNum"]."'";
    $result = mysqli_query($connector, $sql);

    //get results from database
    while($row = mysqli_fetch_array($result)) {
        $output = '<div class="pt-2 pl-2 pr-2">
                  <img src="data:image/jpeg;base64,'.base64_encode($row['image']).'" style="width:75px; height: 100px;" class="float-right pb-2">
                        <div class="float-left text-monospace  font-weight-bold">'.
                            '<lable>&diams; Name: </lable>'.$row["fname"]." ".$row["lname"].'<br>'.
                            '<lable>&diams; Telephone: </lable>'.$row["telephone"].'<br>'.
                            '<lable>&diams; NIC NO: </lable>'.base64_decode($row["nic"]).'<br>'.
                            '<lable>&diams; Hired Date: </lable>'.$row["hireDate"].
                        '</div>
                  </div>';
    }
    echo $output;
}
?>
