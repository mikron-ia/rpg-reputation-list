<?php

return [
    "interface" => [
        "title" => "Reputation Board",
        "welcome" => "Welcome to Reputation Board",
    ],

    /*
     * This describes list of database engines available
     *
     * THIS PART SHOULD NOT BE CHANGED BY OTHER CONFIG FILES
     */
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
        /*
         * This describes list of authentication ways, along with their class suffixes
         *
         * THIS PART SHOULD NOT BE CHANGED BY OTHER CONFIG FILES
         */
        'authenticationMethodReference' => [
            'auth-simple' => 'simple',
        ],
    ],

    /*
     * Methods to calculate specific values derived from raw reputation numbers, in form of `pack.methodName`
     *
     * They all receive list of reputation numbers and data calculated by previous methods; they are executed in
     * the order they are listed
     *
     * All config files should list a complete list they intend to use, not relying on those in higher config files;
     * due to design decision, this section is completely overwritten if next level config file also has one
     */
    'calculation' => [
        'generic.calculateBasic'
    ],

    /*
     * Default logging system
     *
     * Any other used must comply to LoggerInterface
     */
    'logging' => [
        'logfile' => __DIR__.'/../build/log/complete.log',
        'projectNameForLogging' => 'rpg-reputation-list',
        'logLevel' => \Monolog\Logger::WARNING,
        'logLevelDebug' => \Monolog\Logger::NOTICE,
    ],
];
