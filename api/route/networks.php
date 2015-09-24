<?php

/* Display of networks, with descriptions */
$app->get('/networks', function() use ($app) {
    $result = [];
    return json_encode($result);
});