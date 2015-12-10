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
    /*
     * Methods of reputation calculations used by the system, in form of `pack.methodName`
     * They all receive list of reputation numbers, and current state - all already calculated data
     * They are executed in the order they are listed, which matters if previous ones provide data for latter ones
     *
     * All systems should list a complete list, not relaying on previous ones
     */
    'calculation' => [],
];
