<!-- PROFILE INFO AND LOGOUT BUTTON -->
<?php

include 'database_link.php';
include 'database_queries.php';
include dirname(__DIR__) . '\pages\profile-page.html';

session_start();

if ($_GET) {
    echo ("<script>ready(" . $_GET['id'] . ")</script>");
}

if ($_POST) {
    $id = $_POST["id"];
    $dir = dirname(__DIR__) . "\uploads";
    $file = $_FILES["fileToUpload"]["name"];

    //error check
    if ($_FILES["fileToUpload"]["error"]) {
        echo ("<script>console.log('file too large')</script>");
        $uploadOk = 0;
    }
    //does it already exist
    if (!(file_exists($dir . "/" . $file))) {
        //if no then add and proceed
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "$dir/$file"); //put in uploads folder
    }
    //now make the change
    update("user_profiles", ("pfp_url = '".$file."'"), ("id = ".$id));
}


?>