<?php

use Mikron\ReputationList\Domain\Blueprint\Displayable;
use Mikron\ReputationList\Domain\Exception\AuthenticationException;
use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;

/* Reputation data of a particular group via the key */
$app->get(
    '/group/{identificationMethod}/{identificationKey}/{authenticationMethod}/{authenticationKey}/',
    function ($identificationMethod, $identificationKey, $authenticationMethod, $authenticationKey) use ($app) {
        /* Prepare the factories and tokens */
        $connectionFactory = new \Mikron\ReputationList\Infrastructure\Storage\ConnectorFactory($app['config']);
        $groupFactory = new \Mikron\ReputationList\Infrastructure\Factory\Group();
        $authentication = new \Mikron\ReputationList\Infrastructure\Security\Authentication(
            $app['config']['authentication'],
            'hub',
            $authenticationMethod
        );

        /* Check credentials */
        if (!$authentication->isAuthenticated($authenticationKey)) {
            throw new AuthenticationException(
                "Authentication code does not check out",
                "Authentication code $authenticationKey for method $authenticationMethod does not check out"
            );
        }

        /* Verify whether identification method makes sense */
        $method = "retrieveGroupFromDbBy" . ucfirst($identificationMethod);
        if (!method_exists($groupFactory, $method)) {
            throw new ExceptionWithSafeMessage(
                'Error: "' . $identificationMethod . '" is not a valid way for object identification'
            );
        }

        /* Prepare data and start the factory */
        $connection = $connectionFactory->getConnection();

        /**
         * @var Displayable $group
         */
        $group = $groupFactory->$method(
            $connection,
            $app['logger'],
            $app['networks'],
            $identificationKey,
            $app['config']['calculation']
        );

        /* Cook and return the JSON */
        $output = new \Mikron\ReputationList\Domain\Service\Output(
            "Groupal profile",
            "This is a reputation characteristic of a chosen group",
            $group->getCompleteData()
        );
        return $app->json($output->getArrayForJson());
    }
);