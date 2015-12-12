<?php

return [
    'dbEngine' => '',
    'mysql' => [
        'dbname' => '',
        'user' => '',
        'password' => '',
        'host' => '',
        'driver' => 'pdo_mysql',
        /* NOTE: Following appears to be necessary for correct UTF-8 database handling */
        'driverOptions' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'],
    ],
    'debug' => false,
    'authentication' => [
        'hub' => [
            'allowedStrategies' => ['simple'],
            'settingsByStrategy' => [
                'simple' => [
                    'authenticationMethod' => '',
                    'authenticationKey' => null,
                ]
            ]
        ],
        'manager' => [
            'allowedStrategies' => ['simple'],
            'settingsByStrategy' => [
                'simple' => [
                    'authenticationMethod' => '',
                    'authenticationKey' => null,
                ]
            ]
        ],
    ],
    'logging' => [
        'logfile' => __DIR__.'/../build/log/complete.log',
        'projectNameForLogging' => 'rpg-reputation-list',
        'logLevel' => \Monolog\Logger::WARNING,
        'logLevelDebug' => \Monolog\Logger::NOTICE,
    ],
];
