<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("functions.php");
include("../conf.php");

    $currentDirectory = getcwd();
    $uploadDirectory = "/uploads/";

    $errors = []; // Store errors here

    $fileExtensionsAllowed = ['csv']; // These will be the only file extensions allowed 
    $tablename = $_POST['table_name'];
    $fileName = $_FILES['the_file']['name'];
    $fileSize = $_FILES['the_file']['size'];
    $fileTmpName  = $_FILES['the_file']['tmp_name'];
    $fileType = $_FILES['the_file']['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));

    $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName); 

    if (isset($_POST['submit'])) {

      if (! in_array($fileExtension,$fileExtensionsAllowed)) {
        $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
      }

      if ($fileSize > 4000000) {
        $errors[] = "File exceeds maximum size (4MB)";
      }

      // CHECK FOR DUPLICATES

      if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
        //   echo "The file " . basename($fileName, '.csv') . " has been uploaded";

          // add to DB
          $prefix = "dd2";
          $dbname = $prefix . "_" . randomString();
          if(!loadCsv($fileName, $dbname)) { 
            echo "There was a problem loading that file"; 
          }
          else { 
          $insert = "INSERT into $tableList (db_name, table_name) VALUES ('$dbname', '$tablename')";
  
        $db->query($insert);
        echo "File has been loaded. <a href='../manage/'>Click here</a> to return to list of data tables";
          }

        } else {
          echo "An error occurred. Please contact the administrator.";
        }
      } else {
        foreach ($errors as $error) {
          echo $error . "These are the errors" . "\n";
        }
      }

    }
?>