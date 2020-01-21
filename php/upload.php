<?php

include 'database_link.php';
include 'database_queries.php';
include dirname(__DIR__) . '\pages\main-page.html';

$target_dir = getcwd()."\uploads";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

    //err check
    if($check !== false) {
        //File is an image
        $uploadOk = 1;
    } else {
        //File is not an image
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if($uploadOk == 1){
        echo("File Uploaded! <br>");
        $file = $_FILES["fileToUpload"]["name"];
        $tmp_name = $_FILES["fileToUpload"]["tmp_name"];
        echo("<br> Target Dir: ");
        echo($target_dir);
        move_uploaded_file($tmp_name, "$target_dir/$file");
        add("images", ["upload_path"], [$file]);
    }
}

?>