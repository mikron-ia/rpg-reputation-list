<?php

/* Reputation data of a particular person */
$app->get('/person/{id}', function($id) use ($app) {
    $result = [
        "title" => "Personal profile",
        "description" => "This is a reputation characteristic of a chosen person",
        "content" => []
    ];

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


    $result['content'] = $person->getCompleteData();

    return $app->json($result);
});
