<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

session_start();
$user_created = false;

if (!empty($_POST)) { //if input form is not empty (input is named _POST in html)

    $username = $_POST['username'];

    if (strpos($username, '@') !== false && strpos($username, '.com') !== false) {

        echo("check");
        $userExists = check_user($_POST['username']);
        if ($userExists) {
            echo ("this user exists");
        } else {
            $_SESSION['username'] = $_POST['username'];
            add("users", ["username", "password"], [$_POST['username'], $_POST['password']]);
            add("user_profiles", ["first_name", "last_name"], [$_POST['first_name'], $_POST['last_name']]);
            $user_created = true;
        }
    } else {
        echo ("username must be an email");
    }
}

if (!$user_created) {
    include $cd.'/pages/new-user-page.html';
} else {
    echo("okay!");
    header("Location: /php/main.php");
}
