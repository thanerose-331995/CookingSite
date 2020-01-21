<?php

include dirname(__DIR__) . '\database_link.php';
include dirname(__DIR__) . '\database_queries.php';

//GET COMMENTS

if($_GET){
    $res = get("*", "comments", ("post_id = ".$_GET['postID']));
    echo(json_encode($res));
}

?>