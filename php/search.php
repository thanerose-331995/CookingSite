<?php

include 'database_link.php';
include 'database_queries.php';
include dirname(__DIR__) . '\pages\search.html';

session_start();

if($_GET){
    echo ("<script>ready('" . $_GET['search'] . "')</script>");
}

// if($_POST){
//     echo("post");
// }


?>