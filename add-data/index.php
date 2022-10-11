<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataDesk Add File</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
<main>
    
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Table name: <input type-="text" name="table_name" id="table_name"><br/><br/>
      Select file to upload
      <input type="file" name="the_file" id="fileToUpload"><br/><br />
      <input type="submit" value="Upload File" name="submit">
    </form>

</main>  
    
</body>
</html>