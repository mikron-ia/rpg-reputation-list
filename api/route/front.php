<?php

$app->get('/', function (Silex\Application $app) {
    $result = [
        "title" => "Front page",
        "description" => "This is basic front page. Please choose a functionality you wish to access from 'content' area",
        "content" => [
            [
                "url" => "networks",
                "description" => "Lists all networks available"
            ]
        ]
    ];

    return json_encode($result);
});
