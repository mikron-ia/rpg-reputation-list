<?php

/* Reputation data of a particular person */
$app->get('/person/{id}', function ($id) use ($app) {
    $dbEngine = $app['config.deploy']['dbEngine'];
    $dbClass = '\Mikron\ReputationList\Infrastructure\Storage\\' . $app['config.main']['databaseReference'][$dbEngine] . 'Storage';
    $connection = new $dbClass($app['config.deploy'][$dbEngine]);

    $factory = new \Mikron\ReputationList\Infrastructure\Factory\Person();

    $person = $factory->retrievePersonFromDb($connection, $id);

    $output = new \Mikron\ReputationList\Domain\Service\Output(
        "Personal profile",
        "This is a reputation characteristic of a chosen person",
        $person->getCompleteData()
    );

    return $app->json($output->getArrayForJson());
});
