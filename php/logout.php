<!-- LOGOUT -->

<?php

session_start();

session_destroy(); //kill session and return to start page

// $cd = $_SERVER["DOCUMENT_ROOT"];

header("Location: /login.php");

?>