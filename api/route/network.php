<?php

/* Display of rep levels for various people across the network */
$app->get('/network/{id}/', function ($id) use ($app) {
    if (!$app['config']['debug']) {
        throw new \Mikron\ReputationList\Domain\Exception\NotImplementedYetException(
            "This functionality is considered for implementation in a future release",
            "This functionality is considered for implementation in a future release; this message should not have been triggered, as this should not have activated in debug environment"
        );
    }

    $output = new \Mikron\ReputationList\Domain\Service\Output(
        "Reputation network",
        "This is a list of all reputations in single network",
        []
    );

    return $app->json($output->getArrayForJson());
});
