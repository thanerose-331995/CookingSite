<!-- INCLUDE FEED AND BUTTON TO GO TO PROFILE -->
<?php

include 'database_link.php';
include 'database_queries.php';
include dirname(__DIR__) . '\pages\main-page.html';

//session_start();

if ($_POST) {
    $uploadOk = 1;

    // -- ERR CHECKING
    if ($_POST['title'] == "" || $_POST['content'] == "") {
        echo ("<script>console.log('title/content missing')</script>");
        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload"]["name"] == "") {
        echo ("<script>console.log('image missing')</script>");
        $uploadOk = 0;
    } else {
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
    }

    if ($uploadOk) {
        echo ("<script>console.log('added post')</script>");

        // -- UPLOAD POST
        $id['id'] = $_POST['userID'];
        $date = date("d/m/y G:i:s");
        add("posts", ["title", "content", "user_id", "dT", "steps", "ingredients"], [$_POST['title'], $_POST['content'], $id['id'], $date, $_POST['rec'], $_POST['ing']]);
        $postID = (get_id("posts", "dT", $date))['id'];

        // -- UPLOAD FILE
        add("images", ["upload_path"], [$file]); //send to server
        $imgID = (get_id("images", "upload_path", $file))['id'];
        add("post_images", ["post_id", "image_id"], [$postID, $imgID]); //create link

        // -- UPLOAD TAGS
        $tags = explode(',', $_POST["tags"]);
        foreach ($tags as $tag) { //for each tag
            if (!check_tag($tag)) {
                add("tags", ["name"], [$tag]); //add if doesnt already exist
            }
            $tagID = (get_id("tags", "name", $tag))['id']; //find id
            add("post_tags", ["post_id", "tag_id"], [$postID, $tagID]); //add link
        }
    }
}



?>