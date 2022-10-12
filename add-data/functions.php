<?php

function randomString() { 
    // Need a better random alphanumeric generator
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function loadCsv($filename, $dbname, $tablename) { 
    global $db, $tableList, $insertBatchCount;
  
    $uploads = getcwd() . "/uploads/";
    $filelink = $uploads . $filename;
    // create table 
    $csv = array_map("str_getcsv", file($filelink,FILE_SKIP_EMPTY_LINES));
        $keys = array_shift($csv);
        $create = "CREATE table $dbname (";
        foreach($keys as $key) { 
            $create .= "`$key` varchar(255), ";
        }
        $create .= "ddid int(10) NOT NULL AUTO_INCREMENT, PRIMARY KEY (ddid))";
        $create = $db->query($create);

// add data ==========================================================

$file = fopen($filelink, "r");
$defaultinsert = "INSERT into $dbname (";
    foreach($keys as $key) { 
        $defaultinsert .= "`$key`, ";
    }
    $defaultinsert = rtrim($defaultinsert, ", ");
    $defaultinsert .= ") VALUES (";

$insert = $defaultinsert;

$getData = fgetcsv($file, 10000, ","); 
$count = 0; 
$loop = 0;

while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
    foreach($getData as $data) { 
        $insert .= "'$data', ";
    }
    $insert = rtrim($insert, ", ");
    $insert .= ")";
    
    if($count == $insertBatchCount + 1) { 
        $count = 0; 
        $db -> query($insert);
        $insert = $defaultinsert;} 
        else { 
            $insert .= ", (";
            $count++; 
        }
    }

        $add = "INSERT into $tableList (db_name, table_name) VALUES ('$dbname', '$tablename')";
        $add = $db->query($add);

return true;
} 

?>