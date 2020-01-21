<?php

include 'database_link.php';
include 'database_queries.php';

session_start();

if($_GET['type'] == "username"){
    $username = $_SESSION['username'];
    $id = get("id", "users", ("username = '".$username."'"));
    $id = $id[0]['id'];
}else{
    $id = $_GET['user_id'];
}
$res = (get_profile($id));
echo(json_encode($res));

?>