<!-- LOGOUT -->

<?php

session_start();

session_destroy(); //kill session and return to start page

include 'login.php';

?>