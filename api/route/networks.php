<?php

/* Display of networks, with descriptions */
$app->get('/networks/', function () use ($app) {
    $result = [
        "title" => "Reputation networks",
        "description" => "This is a list of all reputation networks that are handled by this system",
        "content" => []
    ];

    $networks = $app['networks'];

    foreach ($networks as $network) {
        $result['networks'][] = $network->getCompleteData();
    }

    return $app->json($result);
});
