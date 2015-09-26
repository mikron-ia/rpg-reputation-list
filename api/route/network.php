<?php

/* Display of rep levels for various people across the network */
$app->get('/network/{id}', function($id) use ($app) {
    $result = [
        "title" => "Reputation network",
        "description" => "This is a list of all reputations in single network",
        "content" => []
    ];

    return json_encode($result);
});
