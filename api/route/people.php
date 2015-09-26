<?php

/* List of people available for display, along with their IDs */
$app->get('/people', function() use ($app) {
    $result = [];
    return json_encode($result);
});
