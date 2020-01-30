<?php
$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';
//ADD TO COMMENT TABLE
session_start();

if($_GET){
    $newLikes = (int)$_GET['current']+1;
    if($_GET['type'] == "COMMENT"){
        update("comments", ("likes=".$newLikes), ("id=".$_GET['id']));
    }
    else if($_GET['type'] == "POST"){
        update("posts", ("likes=".$newLikes), ("id=".$_GET['id']));
    }
}

?>