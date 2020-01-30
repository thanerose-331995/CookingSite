<!-- INCLUDE LOGIN FORM (USER CAN CHOOSE LOGIN OR CREATE NEW USER) -->
<!-- IF LOGGED IN SEND STRAIGHT TO MAIN -->

<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

session_start();

if (!empty($_POST)) { //if input form is not empty (err check)

    $res = login($_POST['username'], $_POST['password']);
    // var_dump($res);
    if ($res) { //if result 1 (true) (because our find_user() function should find 1 result)
        $_SESSION['logged_in'] = true;
    }else{
        echo('user does not exist');
    }
}

$logged_in = $_SESSION['logged_in'] ?? false; //set to logged_in or false?


if ($logged_in) {
    $_SESSION['username'] = $_POST['username'];
    header("Location: /php/main.php");
} else {
    include $cd."/pages/login-page.html";
}

?>