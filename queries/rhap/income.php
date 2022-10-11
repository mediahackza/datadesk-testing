<?php 
header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/json');
include("../../conf.php");
$data = array();

// Hourly Minimum Wage
$query = "select * from dd2_1tvy83vi15 where category = 'hourly min wage' order by month_year ASC";
$result = $db->query($query);
while($row = $result->fetch_assoc()) {
    $data['minWageHourly'][] = $row;
}

// Monthly Minimum Wage
$query = "select * from dd2_1tvy83vi15 where category = 'Minimum wage' order by month_year ASC";
$result = $db->query($query);
while($row = $result->fetch_assoc()) {
    $data['minWageMonthly'][] = $row;
}

echo json_encode($data);
?>