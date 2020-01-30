<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

if($_GET){
    remove($_GET['table'], $_GET['column'], $_GET['value']);
    if($_GET['table'] == "users"){
        $res = get_profile($_GET['value']);
        remove("user_profiles", "id", $res['id']);
        remove("posts", "user_id", $res['id']);
        echo($res['id']);
        remove("comments", "user_id", $res['id']);
    }
}

?>