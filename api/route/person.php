<?php

/* Reputation data of a particular person */
$app->get('/person/{id}', function ($id) use ($app) {
    $dbConfig = $app['config.deploy']['mysql'];

    $connection = new \Mikron\ReputationList\Infrastructure\Storage\MySqlStorage(
        $dbConfig['url'],
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['dbname'],
        $dbConfig['options']
    );

    $factory = new \Mikron\ReputationList\Infrastructure\Factory\Person();

    $person = $factory->retrievePersonFromDb($connection, $id);

    $output = new \Mikron\ReputationList\Domain\ValueObject\Output(
        "Personal profile",
        "This is a reputation characteristic of a chosen person",
        $person->getCompleteData()
    );

    return $app->json($output->getArrayForJson());
});
