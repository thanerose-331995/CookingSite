<?php
function db()
{
    //connection to database
    $host       = '91.103.219.224';
    $port       = '3306';
    $db         = 'webapps1_rosa';
    $username   = 'webapps1_rosa';
    $password   = 'rVvE8cay';
    $charset    = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    $pdo = new PDO($dsn, $username, $password, [ //telling the program what exceptions to throw if an error occurs
        PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}