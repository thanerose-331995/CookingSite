<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

session_start();

$is_user = false;
$username = $_SESSION['username'];
$userID = (get("id", "users", ["username", $username]))[0]['id'];

if($_GET['type'] == "username"){
    $id = $userID;
}else{
    $id = $_GET['user_id'];
}
$res = (get_profile($id));
// var_dump($res);
if($res['id'] == $userID){
    $is_user = true;
}
$res["is_user"] = $is_user;
echo(json_encode($res));

?>