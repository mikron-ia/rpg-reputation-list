<?php

/* Reputation data of a particular person */
$app->get('/person/{id}', function($id) use ($app) {
    $result = [
        "title" => "Personal profile",
        "description" => "This is a reputation characteristic of a chosen person",
        "content" => []
    ];

    return $app->json($result);
});
