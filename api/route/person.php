<?php

use Mikron\ReputationList\Domain\Blueprint\Displayable;
use Mikron\ReputationList\Domain\Exception\AuthenticationException;
use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;

/* Reputation data of a particular person via the key */
$app->get(
    '/person/{identificationMethod}/{identificationKey}/{authenticationMethod}/{authenticationKey}/',
    function ($identificationMethod, $identificationKey, $authenticationMethod, $authenticationKey) use ($app) {
        /* Prepare the factories and tokens */
        $connectionFactory = new \Mikron\ReputationList\Infrastructure\Storage\ConnectorFactory($app['config']);
        $personFactory = new \Mikron\ReputationList\Infrastructure\Factory\Person();
        $authentication = new \Mikron\ReputationList\Infrastructure\Security\Authentication(
            $app['config']['authentication'],
            'hub',
            $authenticationMethod
        );

        if (class_exists($app['config']['calculator'])) {
            $calculator = new $app['config']['calculator'];
        } else {
            throw new \Mikron\ReputationList\Domain\Exception\ConfigurationException(
                "Missing calculator class",
                "Missing calculator class. Please set 'calculator' to correct class value."
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
        $method = "retrievePersonFromDbBy" . ucfirst($identificationMethod);
        if (!method_exists($personFactory, $method)) {
            throw new ExceptionWithSafeMessage(
                'Error: "' . $identificationMethod . '" is not a valid way for object identification'
            );
        }

        /* Prepare data and start the factory */
        $connection = $connectionFactory->getConnection();

        /**
         * @var Displayable $person
         */
        $person = $personFactory->$method(
            $connection,
            $app['logger'],
            $app['networks'],
            $identificationKey,
            $calculator
        );

        /* Cook and return the JSON */
        $output = new \Mikron\ReputationList\Domain\Service\Output(
            "Personal profile",
            "This is a reputation characteristic of a chosen person",
            $person->getCompleteData()
        );
        return $app->json($output->getArrayForJson());
    }
)->bind('personDefault');

/* Redirect for direct route to person */
$app->get(
    'person/{key}/{authenticationMethod}/{authenticationKey}/',
    function ($key, $authenticationMethod, $authenticationKey) use ($app) {
        return $app->redirect(
            $app["url_generator"]->generate(
                "personDefault",
                [
                    'identificationMethod' => 'key',
                    'identificationKey' => $key,
                    'authenticationMethod' => $authenticationMethod,
                    'authenticationKey' => $authenticationKey
                ]
            ),
            307
        );
    }
);
