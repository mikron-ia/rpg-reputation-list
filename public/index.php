<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application;

$app->get('/', function (Silex\Application $app)  {
    return  "<h1>Reputation List</h1>".
    "<p>This is basic front page. Nothing of use can be found here at the moment. Please return once we fixed this.</p>";
});

$app->run();