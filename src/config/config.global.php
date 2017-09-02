<?php

return [
    'configuration' => [
        'database' => [
            'provider' => 'mysql',
            'host' => 'localhost',
            'dbname' => 'video_place',
            'username' => 'root',
            'password' => '',
            'extra' => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
            ],
        ],
    ],
];