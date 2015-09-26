<?php

/* Reputation data of a particular person */
$app->get('/person/{id}', function($id) use ($app) {
    $result = [];
    return json_encode($result);
});
