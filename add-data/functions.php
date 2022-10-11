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

function loadCsvFget($filename, $dbname, $tablename) { 
global $db, $tableList;
$uploads = getcwd() . "/uploads/";
$fileLink = $uploads . $filename;
// create table 
$csv = array_map("str_getcsv", file($fileLink,FILE_SKIP_EMPTY_LINES));
    $keys = array_shift($csv);
    $create = "CREATE table $dbname (";
    foreach($keys as $key) { 
        $create .= "`$key` varchar(255), ";
    }
    $create .= "ddid int(10) NOT NULL AUTO_INCREMENT, PRIMARY KEY (ddid))";
    $create = $db->query($create);
// add data

$file = fopen($fileLink, "r");
          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            if($notFirstLine) { 
                $insert = "INSERT into $dbname (";
                foreach($keys as $key) { 
                    $insert .= "`$key`, ";
                }
                $insert = rtrim($insert, ", ");
                $insert .= ") VALUES (";
                foreach($getData as $data) { 
                    $insert .= "'$data', ";
                }
                $insert = rtrim($insert, ", ");
                $insert .= ")";
                $insert = $db->query($insert);
            }
            $notFirstLine = true;
           
          }

          // add to table list
        $add = "INSERT into $tableList (db_name, table_name) VALUES ('$dbname', '$tablename')";
        $add = $db->query($add);


          header("Location: ../manage/");
} 

// load file into MySQL
// Uses loadinfile which doesn't work widely
function loadCsv($filename, $dbname) { 
    
    global $db;
    $uploads = getcwd() . "/uploads/";

    $csv = array_map("str_getcsv", file($uploads . $filename,FILE_SKIP_EMPTY_LINES));
    $keys = array_shift($csv);
    $create = "CREATE table $dbname (";
    foreach($keys as $key) { 
        $create .= "`$key` varchar(255), ";
    }
    $create .= "ddid int(10) NOT NULL AUTO_INCREMENT, PRIMARY KEY (ddid))";
    $create = $db->query($create);

$fileLink = $uploads . $filename;

$query = "LOAD DATA LOCAL INFILE '$fileLink' INTO TABLE $dbname FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"; 

unlink($fileLink); // Remove uploaded file


if($db->query($query)) { 
    return true;
}
else { 
   return false;
}




}

?>