<?php 
include("../conf.php"); 
$id = $_GET['id']; 

$getdetails = "SELECT db_name from $tableList WHERE id = $id";
$result = $db->query($getdetails);
$row = $result->fetch_row();
$dbname = $row[0];

// delete db
$delete = "DROP TABLE $dbname";
$db->query($delete);

// delete from tablelist
$delete = "DELETE from $tableList WHERE id = $id";
$db->query($delete);

header("Location: ./");

?>