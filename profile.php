<!-- PROFILE INFO AND LOGOUT BUTTON -->
<?php

include 'database_link.php';
include 'database_queries.php';
include 'pages/profile-page.html';

session_start();

$result = get_profile($_SESSION['username']);
echo("First Name: ".$result['first_name']." Surname: ".$result["last_name"]." ID: ".$result["id"]);
get_posts($_SESSION['username']);

?>