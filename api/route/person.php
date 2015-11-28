<?php

/* Reputation data of a particular person via DB ID */
$app->get('/person/id/{id}/', function ($id) use ($app) {
    $connectionFactory = new \Mikron\ReputationList\Infrastructure\Storage\ConnectorFactory($app['config']);
    $personFactory = new \Mikron\ReputationList\Infrastructure\Factory\Person();

    $connection = $connectionFactory->getConnection();
    $person = $personFactory->retrievePersonFromDbById($connection, $app['networks'], $id);

    $output = new \Mikron\ReputationList\Domain\Service\Output(
        "Personal profile",
        "This is a reputation characteristic of a chosen person",
        $person->getCompleteData()
    );

    return $app->json($output->getArrayForJson());
})->bind('personById');;

/* Reputation data of a particular person via the key */
$app->get('/person/key/{key}/', function ($key) use ($app) {
    $connectionFactory = new \Mikron\ReputationList\Infrastructure\Storage\ConnectorFactory($app['config']);
    $personFactory = new \Mikron\ReputationList\Infrastructure\Factory\Person();

    $connection = $connectionFactory->getConnection();
    $person = $personFactory->retrievePersonFromDbByKey($connection, $app['networks'], $key);

    $output = new \Mikron\ReputationList\Domain\Service\Output(
        "Personal profile",
        "This is a reputation characteristic of a chosen person",
        $person->getCompleteData()
    );

    return $app->json($output->getArrayForJson());
})->bind('personByKey');

/* Redirect for direct route to person */
$app->get('person/{key}/', function ($key) use ($app) {
    return $app->redirect($app["url_generator"]->generate("personByKey", ['key' => $key]), 307);
});
