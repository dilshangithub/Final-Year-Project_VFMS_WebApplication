<?php
require_once 'databaseConnector.php';
error_reporting(0);

if(isset($_POST["vehicleNum"])) {
        $sql = "SELECT * FROM vehicle WHERE regNo = '".$_POST["vehicleNum"]."'";
        $result = mysqli_query($connector, $sql);

        //get results from database
        while($row = mysqli_fetch_array($result)) {
            $output = '<div class="pt-2 pl-2 pr-2">
                  <img src="data:image/jpeg;base64,'.base64_encode($row['image']).'" style="width:120px; height: 100px;" class="float-right pb-2">
                        <div class="float-left text-monospace  font-weight-bold">'.
                            '<lable>&diams; Vehicle Type: </lable>'.$row["type"].'<br>'.
                            '<lable>&diams; Container Type: </lable>'.$row["containerType"].'<br>'.
                            '<lable>&diams; Vehicle Capacity: </lable>'.$row["capacity"].'<br>'.
                            '<lable>&diams; Number of Tyres: </lable>'.$row["tyres"].
                        '</div>
                   </div>';
        }
        echo $output;
}
?>
