<?php

$app->get('/', function (Silex\Application $app) {
    $result = [
        "title" => "Front page",
        "description" => "This is basic front page. Please choose a functionality you wish to access from 'content' area",
        "content" => [
            [
                "url" => "networks",
                "description" => "Lists all networks available in the system"
            ],
            [
                "url" => "people",
                "description" => "Lists all people whose profile the system has"
            ],
            [
                "url" => "people",
                "description" => "Lists data regarding specific person",
                "note" => "Does not provide complete data yet"
            ]
        ]
    ];

    return $app->json($result);
});
