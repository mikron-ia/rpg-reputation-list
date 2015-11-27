<?php

/* List of people available for display, along with their IDs */
$app->get('/people/', function () use ($app) {
    $dbEngine = $app['config']['dbEngine'];
    $dbClass = '\Mikron\ReputationList\Infrastructure\Storage\\'
        . $app['config']['databaseReference'][$dbEngine] . 'StorageEngine';
    $connection = new $dbClass($app['config'][$dbEngine]);

    $connectionFactory = new \Mikron\ReputationList\Infrastructure\Storage\ConnectorFactory($app['config']);

    $connection = $connectionFactory->getConnection();

    $factory = new \Mikron\ReputationList\Infrastructure\Factory\Person();

    $peopleObjects = $factory->retrieveAllFromDb($connection);
    $peopleList = [];

    foreach ($peopleObjects as $person) {
        $peopleList[] = $person->getSimpleData();
    }

    $output = new \Mikron\ReputationList\Domain\Service\Output(
        "List",
        "This is a list of people available for peruse",
        $peopleList
    );

    return $app->json($output->getArrayForJson());
});
