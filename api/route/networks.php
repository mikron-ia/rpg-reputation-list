<?php

/* Display of networks, with descriptions */
$app->get('/networks/', function () use ($app) {
    $networks = $app['networks'];
    $arrays = [];

    foreach ($networks as $network) {
        $arrays[] = $network->getCompleteData();
    }

    $output = new \Mikron\ReputationList\Domain\ValueObject\Output(
        "Reputation networks",
        "This is a list of all reputation networks that are handled by this system",
        $arrays
    );

    return $app->json($output->getArrayForJson());
});
