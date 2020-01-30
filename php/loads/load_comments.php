<?php
$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

//GET COMMENTS

if($_GET){
    $res = get("*", "comments", ["post_id",$_GET['postID']]);
    echo(json_encode($res));
}

?>