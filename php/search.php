<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';
include $cd.'/pages/search.html';

session_start();

if($_GET){
    echo ("<script>ready('" . $_GET['search'] . "')</script>");
}

// if($_POST){
//     echo("post");
// }


?>