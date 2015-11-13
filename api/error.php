<?php

$app->error(function (Exception $exception) use ($app) {
    $result = [
        "title" => "Error",
        "description" => "There has been an error",
        "content" => []
    ];

    if ($app['config.deploy']['debug']) {
        $result['content'][] = $exception->getMessage();
    }

    var_dump($result); die;

    return $app->json($result);
});
