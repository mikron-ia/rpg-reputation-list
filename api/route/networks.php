<?php

/* Display of networks, with descriptions */
$app->get('/networks/', function () use ($app) {
    $networks = $app['networks'];

    $result = [
        "networks" => []
    ];

    foreach ($networks as $network) {
        $result['networks'][] = $network->getArrayForJSON();
    }

    return json_encode($result);
});