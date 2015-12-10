<?php

use Mikron\ReputationList\Domain\Exception\AuthenticationException;

/* Reputation data of a particular person via DB ID */
$app->get(
    '/person/id/{id}/{authenticationMethod}/{authenticationKey}/',
    function ($id, $authenticationMethod, $authenticationKey) use ($app) {
        $connectionFactory = new \Mikron\ReputationList\Infrastructure\Storage\ConnectorFactory($app['config']);
        $personFactory = new \Mikron\ReputationList\Infrastructure\Factory\Person();
        $authentication = new \Mikron\ReputationList\Infrastructure\Security\Authentication(
            $app['config']['authentication'],
            'hub',
            $authenticationMethod,
            $authenticationKey
        );

        if ($authentication->isAuthenticated()) {
            $connection = $connectionFactory->getConnection();
            $person = $personFactory->retrievePersonFromDbById($connection, $app['monolog'], $app['networks'], $id, $app['config']['calculation']);

            $output = new \Mikron\ReputationList\Domain\Service\Output(
                "Personal profile",
                "This is a reputation characteristic of a chosen person",
                $person->getCompleteData()
            );

            return $app->json($output->getArrayForJson());
        } else {
            throw new AuthenticationException("Authentication code does not check out");
        }
    }
)->bind('personById');

/* Reputation data of a particular person via the key */
$app->get(
    '/person/key/{key}/{authenticationMethod}/{authenticationKey}/',
    function ($key, $authenticationMethod, $authenticationKey) use ($app) {
        $connectionFactory = new \Mikron\ReputationList\Infrastructure\Storage\ConnectorFactory($app['config']);
        $personFactory = new \Mikron\ReputationList\Infrastructure\Factory\Person();
        $authentication = new \Mikron\ReputationList\Infrastructure\Security\Authentication(
            $app['config']['authentication'],
            'hub',
            $authenticationMethod,
            $authenticationKey
        );

        if ($authentication->isAuthenticated()) {
            $connection = $connectionFactory->getConnection();
            $person = $personFactory->retrievePersonFromDbByKey($connection, $app['monolog'], $app['networks'], $key, $app['config']['calculation']);

            $output = new \Mikron\ReputationList\Domain\Service\Output(
                "Personal profile",
                "This is a reputation characteristic of a chosen person",
                $person->getCompleteData()
            );

            return $app->json($output->getArrayForJson());
        } else {
            throw new AuthenticationException("Authentication code does not check out");
        }
    }
)->bind('personByKey');

/* Redirect for direct route to person */
$app->get(
    'person/{key}/{authenticationMethod}/{authenticationKey}/',
    function ($key, $authenticationMethod, $authenticationKey) use ($app) {
        return $app->redirect(
            $app["url_generator"]->generate(
                "personByKey",
                [
                    'key' => $key,
                    'authenticationMethod' => $authenticationMethod,
                    'authenticationKey' => $authenticationKey
                ]
            ),
            307
        );
    }
);
