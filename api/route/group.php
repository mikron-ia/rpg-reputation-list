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

        if(!isset($app['config']['calculator']['group'])) {
            throw new \Mikron\ReputationList\Domain\Exception\ConfigurationException(
                "Missing calculator configuration",
                "Missing calculator configuration. Please set correct class value in calculator.group configuration."
            );
        }

        if (class_exists($app['config']['calculator']['group'])) {
            $calculator = new $app['config']['calculator']['group'];
        } else {
            throw new \Mikron\ReputationList\Domain\Exception\ConfigurationException(
                "Missing calculator class",
                "Missing calculator class. Please set correct class value in calculator.group configuration."
            );
        }

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
            $calculator
        );

        /* Cook and return the JSON */
        $output = new \Mikron\ReputationList\Domain\Service\Output(
            "Group profile",
            "This is a reputation characteristic of a chosen group",
            $group->getCompleteData()
        );
        return $app->json($output->getArrayForJson());
    }
);