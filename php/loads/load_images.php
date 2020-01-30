<?php

$cd = $_SERVER["DOCUMENT_ROOT"];

include $cd.'/php/database_link.php';
include $cd.'/php/database_queries.php';

load_images(($_GET['id']));

?>