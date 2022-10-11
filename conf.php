<?php 

$hostname = "sql31.cpt3.host-h.net"; 
$mysql_login = "tectobhjww_26";
$mysql_password = "NpwqYgwX8Bpdzyh3zdS8";
$database = "datadesk";
$tableList = "table_list";


$db = new mysqli($hostname, $mysql_login, $mysql_password, $database);
if($db->connect_errno > 0){ 
    // echo "Connected to DB";
    // $date = date('Y-m-d H:i:s');
    // error_log("\n\10" . $date . " - Failed to connect to database [" . $db->connect_error ."]", 3, "errors.log"); 
    // $msg = $date . " - Failed to connect to database [" . $db->connect_error ."]";
    // $to = "alastair@mediahack.co.za";
    // $subject = "MHC AUTH Error";
    // mail($to, $subject, $msg);
}
else { 
    // echo "Not connected to DB";
    
}


?>