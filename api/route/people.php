<?php

/* List of people available for display, along with their IDs */
$app->get('/people/', function() use ($app) {
    $result = [
        "title" => "List",
        "description" => "This is a list of people available for peruse",
        "content" => []
    ];

    return json_encode($result);
});
