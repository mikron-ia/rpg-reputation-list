<?php

return [
    "version" => "0.4-dev",
    "interface" => [
        "title" => "Reputation Board",
        "welcome" => "Welcome to Reputation Board",
    ],
    "databaseReference" => [
        'mysql' => "MySql",
        'mongodb' => "MongoDb",
    ],
    'authentication' => [
        'hub' => [
            'allowedStrategies' => [],
            'settingsByStrategy' => []
        ],
        'manager' => [
            'allowedStrategies' => [],
            'settingsByStrategy' => []
        ],
        'authenticationMethodReference' => [
            'auth-simple' => 'simple',
        ],
    ],
];
