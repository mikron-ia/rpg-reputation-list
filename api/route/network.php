<?php

/* Display of rep levels for various people across the network */
$app->get('/network/{id}', function ($id) use ($app) {
    $output = new \Mikron\ReputationList\Domain\Service\Output(
        "Reputation network",
        "This is a list of all reputations in single network",
        []
    );

    return $app->json($output->getArrayForJson());
});
