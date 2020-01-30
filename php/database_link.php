<?php
function db()
{
    //connection to database
    static $pdo;

    if($pdo){
        return $pdo;
    }

    $config = include 'db_config.php';

    extract($config);
    // var_dump($config);
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

    // Connect to database
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);


    return $pdo;
}