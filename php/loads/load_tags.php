<?php

include dirname(__DIR__) . '\database_link.php';
include dirname(__DIR__) . '\database_queries.php';

$res = load_tags($_GET['id']);
return $res;

?>