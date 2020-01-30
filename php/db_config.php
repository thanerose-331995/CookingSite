<?php
// Work out environment from hostname (note use of 'threequals here')
$server = $_SERVER['HTTP_HOST'];

if ((strpos('local', $_SERVER['HTTP_HOST'])) !== false) {
    $env = 'local';
}
else {
    $env = 'live';
}
// var_dump($env);
// Multiple database configs for each env
$config = [
    'db' => [
        // Local db config
        'local' => [
            'host'       => '127.0.0.1',
            'port'       => '3306',
            'db'         => 'my_database',
            'username'   => 'root',
            'password'   =>  null,
            'charset'    => 'utf8mb4'
        ],

        // Live db config
        'live' => [
            'host'      => '91.103.219.224',
            'port'      => '3306',
            'db'        => 'webapps1_rosa',
            'username'  => 'webapps1_rosa',
            'password'  => 'rVvE8cay',
            'charset'    => 'utf8mb4'
        ],
    ]
];

//$config = include 'config.php';
$config = $config['db'][$env];

// This config should be used to connect to the database
return $config;

?>