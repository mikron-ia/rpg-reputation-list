<?php

/* List of people available for display, along with their IDs */
$app->get('/people/', function () use ($app) {
    $result = [
        "title" => "List",
        "description" => "This is a list of people available for peruse",
        "content" => []
    ];

    $dbConfig = $app['config.deploy']['mysql'];

    $connection = new \Mikron\ReputationList\Infrastructure\Storage\MySqlStorage($dbConfig['url'],
        $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname'], $dbConfig['options']);

    $factory = new \Mikron\ReputationList\Infrastructure\Factory\Person();

    $people = $factory->retrieveAllFromDb($connection);

    foreach ($people as $person) {
        $result['content'][] = $person->getSimpleIdentification();
    }

    return json_encode($result);
});
