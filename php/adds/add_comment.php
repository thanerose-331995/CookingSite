<?php

include dirname(__DIR__) . '\database_link.php';
include dirname(__DIR__) . '\database_queries.php';

//ADD TO COMMENT TABLE
session_start();

if($_GET){
        
    $date = date("d/m/y G:i:s");
    $username = $_SESSION['username'];
    $id = get("id", "users", ("username = '".$username."'"));
    $id = $id[0]["id"];
    //echo($username);
    //var_dump(get_profile($id));
    $user = (get_profile($id)['first_name']) ." ". (get_profile($id)['last_name']);
    //var_dump($user);
    $postID = $_GET['postID'];
    $comment = $_GET['comment'];
    $currentComments = get("comments", "posts", ("id = '".$postID."'"));
    //var_dump($currentComments[0]);
    add("comments", ["post_id", "comment", "user_id", "username", "dT"], [$postID, $comment, $id, $user, $date]);
    update("posts", ("comments=".((int)$currentComments[0]["comments"]+1)), ("id=".$postID));
}

?>