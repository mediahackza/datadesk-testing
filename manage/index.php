<?php
include("header.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../conf.php");
$query = "SELECT * from $tableList WHERE db_name LIKE 'dd2_%' ORDER BY db_name ASC";

$result = $db->query($query);
echo "<a href='../add-data/'>Add Data</a>";
echo "<br/><br/>";
echo "<table>";
echo "<tr><th>DB Name</th><th>Table Name</th><th>Type</th><th></th></tr>";
while($row = $result->fetch_assoc()) {
    $dbname = $row['db_name'];
    $tablename = $row['table_name'];
    $type = $row['type']; 
    $deleted = $row['deleted'];
    $id = $row['id'];
    echo "<tr><td>$dbname</td><td>$tablename</td><td><a href='../api/json.php?table=$dbname'>JSON</a></td><td><a href='./delete-table.php?id=$id'>Delete</a></tr>";
}
echo "</table>";


?>