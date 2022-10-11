<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/json');
include("../conf.php");
$table = $_GET['table'];
$data = array();
$query = "SELECT * from $table";
$result = $db->query($query);
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

?>