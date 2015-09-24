<?php

/* Display of rep levels for various people across the network */
$app->get('/network/{id}', function($id) use ($app) {
    $result = [];
    return json_encode($result);
});