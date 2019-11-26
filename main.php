<!-- INCLUDE FEED AND BUTTON TO GO TO PROFILE -->
<?php

include 'database_link.php';
include 'database_queries.php';
include 'pages/main-page.html';

session_start();

if(!empty($_POST)){
    $id = get_id($_SESSION['username']);
    $res = add("posts", ["title", "content", "user_id"], [$_POST["new-post"], $_POST["title"], $id['id']]);
};

get_posts("all");

?>