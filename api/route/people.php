<?php

/* List of people available for display, along with their IDs */
$app->get('/people/{authenticationMethod}/{authenticationKey}/', function ($authenticationMethod, $authenticationKey) use ($app) {
    $connectionFactory = new \Mikron\ReputationList\Infrastructure\Storage\ConnectorFactory($app['config']);
    $peopleFactory = new \Mikron\ReputationList\Infrastructure\Factory\Person();
    $authentication = new \Mikron\ReputationList\Infrastructure\Security\Authentication(
        $app['config']['authentication'],
        'hub',
        $authenticationMethod,
        $authenticationKey
    );

    if ($authentication->isAuthenticated()) {
        $connection = $connectionFactory->getConnection();
        $peopleObjects = $peopleFactory->retrieveAllFromDb($connection);
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
    } else {
        throw new AuthenticationException("Authentication code does not check out");
    }
});
