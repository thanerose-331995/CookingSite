<?php

include dirname(__DIR__) . '\database_link.php';
include dirname(__DIR__) . '\database_queries.php';

session_start();

if($_GET['posts']=="all"){
    echo(get_posts($_GET['posts']));
}else{

    echo(get_posts($_GET['posts']));
}

?>