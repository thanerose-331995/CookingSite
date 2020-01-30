<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

//ADD TO COMMENT TABLE
session_start();

if ($_GET) {

    $date = date("d/m/y G:i:s");
    $username = $_SESSION['username'];
    $id = get("id", "users", ["username", $username]);
    $id = $id[0]["id"];
    
    $user = (get_profile($id)['first_name']) . " " . (get_profile($id)['last_name']);
    
    $postID = $_GET['postID'];
    $comment = $_GET['comment'];
    $currentComments = get("comments", "posts", ["id", $postID]);
    
    add("comments", ["post_id", "comment", "user_id", "username", "dT"], [$postID, $comment, $id, $user, $date]);
    update("posts", ("comments=" . ((int) $currentComments[0]["comments"] + 1)), ("id=" . $postID));
}
