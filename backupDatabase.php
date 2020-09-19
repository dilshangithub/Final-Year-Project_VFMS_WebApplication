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

<?php
//get user id from session variable
$userId = $_SESSION['userID'];
    //validate user's password before backup data base
   $passwordValidation = $_POST['passwordValidation'];
    require_once 'databaseConnector.php';
    $query = "SELECT * FROM user WHERE  telephone ='$userId'";
    $listQueryResult = mysqli_query($connector, $query);

    $valPassword = mysqli_fetch_array($listQueryResult);
    $actualPassword = $valPassword['password'];

    //if the user entered password does not match with actual password, create error message and assign it into session variable.
    if ($passwordValidation!= base64_decode($actualPassword)){
        $_SESSION['passwordError_backup']= 'Your Password Is incorrect. Please Enter Correct Password For Backup Database!';
        header('location: settings_backup.php');
    }
        //get database info
        $mysqlUserName      = "root";
        $mysqlPassword      = "uom";
        $mysqlHostName      = "localhost";
        $DbName             = "yana_database";
        $backup_name        = "yana_database_backup.sql";
        $tables= array("accidentmanagement", "allocatemobile", "driver", "fuelmanagement", "gpscordinate", "insurancemanagement", "licencemanagement","servicemanagement","sparepartsmanagement","tripschedule","user","vehicle");

Export_Database($mysqlHostName,$mysqlUserName,$mysqlPassword,$DbName,  $tables=false, $backup_name=false);

function Export_Database($host,$user,$pass,$name,  $tables=false, $backup_name=false){

    $mysqli = new mysqli($host,$user,$pass,$name);
    $mysqli->select_db($name);
    $mysqli->query("SET NAMES 'utf8'");

    $queryTables = $mysqli->query('SHOW TABLES');
    while($row = $queryTables->fetch_row()) {
        $target_tables[] = $row[0];
    }
    if($tables !== false) {
        $target_tables = array_intersect( $target_tables, $tables);
    }
    foreach($target_tables as $table) {
        $result         =   $mysqli->query('SELECT * FROM '.$table);
        $fields_amount  =   $result->field_count;
        $rows_num=$mysqli->affected_rows;
        $res            =   $mysqli->query('SHOW CREATE TABLE '.$table);
        $TableMLine     =   $res->fetch_row();
        $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

        for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
            while($row = $result->fetch_row()) {
                if ($st_counter%100 == 0 || $st_counter == 0 ) {
                    $content .= "\nINSERT INTO ".$table." VALUES";
                }
                $content .= "\n(";
                for($j=0; $j<$fields_amount; $j++) {
                    $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );
                    if (isset($row[$j])) {
                        $content .= '"'.$row[$j].'"' ;
                    }
                    else {
                        $content .= '""';
                    }
                    if ($j<($fields_amount-1)) {
                        $content.= ',';
                    }
                }
                $content .=")";
                if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {
                    $content .= ";";
                }
                else {
                    $content .= ",";
                }
                $st_counter=$st_counter+1;
            }
        } $content .="\n\n\n";
    }

    $date = date("Y-m-d");
    $backup_name = $backup_name ? $backup_name : $name.".$date.sql";
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header("Content-disposition: attachment; filename=\"".$backup_name."\"");
    echo $content; exit;
}
?>