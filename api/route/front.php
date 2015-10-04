<?php

$app->get('/', function (Silex\Application $app) {
    $output = new \Mikron\ReputationList\Domain\Service\Output(
        "Front page",
        "This is basic front page. Please choose a functionality you wish to access from 'content' area",
        [
            [
                "url" => "networks",
                "description" => "Lists all networks available in the system"
            ],
            [
                "url" => "people",
                "description" => "Lists all people whose profile the system has"
            ],
            [
                "url" => "person/{person_id}",
                "description" => "Lists data regarding specific person",
                "note" => "Does not provide complete data yet"
            ]
        ]
    );

    return $app->json($output->getArrayForJson());
});
