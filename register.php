<?php

include 'database_link.php';
include 'database_queries.php';

session_start();

$user_created = false;

if (!empty($_POST)) { //if input form is not empty (input is named _POST in html)

    $username = $_POST['username'];

    if (strpos($username, '@') && strpos($username, '.com')) {       

        $userExists = check_user($_POST['username']);

        if ($userExists) {
            echo("this user exists");
        } else {
            $_SESSION['username'] = $_POST['username'];
            add("users", ["username", "password"], [$_POST['username'], $_POST['password']]);
            add("user_profiles", ["first_name", "last_name"], [$_POST['first_name'], $_POST['last_name']]);
            $user_created = true;
        }
    } else {
        echo("username invalid");
    }
}

if (!$user_created) {
    include 'pages/new-user-page.html';
} else {
    include 'main.php';
}
