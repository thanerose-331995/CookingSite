<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

session_start();

if($_GET['posts']=="all"){
    echo(get_posts($_GET['posts']));
}else{

    echo(get_posts($_GET['posts']));
}

?>