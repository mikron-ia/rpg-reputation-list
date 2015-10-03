<?php

/* List of people available for display, along with their IDs */
$app->get('/people/', function () use ($app) {
    $dbConfig = $app['config.deploy']['mysql'];

    $connection = new \Mikron\ReputationList\Infrastructure\Storage\MySqlStorage(
        $dbConfig['url'],
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['dbname'],
        $dbConfig['options']
    );

    $factory = new \Mikron\ReputationList\Infrastructure\Factory\Person();

    $peopleObjects = $factory->retrieveAllFromDb($connection);
    $peopleList = [];

    foreach ($peopleObjects as $person) {
        $peopleList[] = $person->getSimpleData();
    }

    $output = new \Mikron\ReputationList\Domain\ValueObject\Output(
        "List",
        "This is a list of people available for peruse",
        $peopleList
    );

    return $app->json($output->getArrayForJson());
});
