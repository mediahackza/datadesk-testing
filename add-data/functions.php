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

// load file into MySQL
function loadCsv($filename, $dbname) { 
    
    global $db;
    $uploads = getcwd() . "/uploads/";

    $csv = array_map("str_getcsv", file($uploads . $filename,FILE_SKIP_EMPTY_LINES));
    $keys = array_shift($csv);
    // create table
    $create = "CREATE table $dbname (";
    foreach($keys as $key) { 
        $create .= "`$key` varchar(255), ";
    }
    $create .= "ddid int(10) NOT NULL AUTO_INCREMENT, PRIMARY KEY (ddid))";
 echo $create;
    $create = $db->query($create);

$fileLink = $uploads . $filename;

$query = "LOAD DATA LOCAL INFILE '$fileLink' INTO TABLE $dbname FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"; 

unlink($filelink); // Remove uploaded file


if($db->query($query)) { 
    return true;
}
else { 
   return false;
}




}

?>