<?php

/* Display of networks, with descriptions */
$app->get('/networks/{authenticationMethod}/{authenticationKey}/', function ($authenticationMethod, $authenticationKey) use ($app) {
    $authentication = new \Mikron\ReputationList\Infrastructure\Security\Authentication(
        $app['config']['authentication'],
        'hub',
        $authenticationMethod,
        $authenticationKey
    );

    if ($authentication->isAuthenticated()) {
        $networks = $app['networks'];
        $arrays = [];

        foreach ($networks as $network) {
            $arrays[] = $network->getCompleteData();
        }

        $output = new \Mikron\ReputationList\Domain\Service\Output(
            "Reputation networks",
            "This is a list of all reputation networks that are handled by this system",
            $arrays
        );

        return $app->json($output->getArrayForJson());
    }
});
