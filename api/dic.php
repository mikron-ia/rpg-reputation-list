<?php

/**
 * Inter-application dependency injections
 */

/* Reputation networks */
$app['networks'] = $app->share(function ($app) {
    if (isset($app['config']['reputations'])) {
        $factory = new \Mikron\ReputationList\Infrastructure\Factory\ReputationNetwork();
        $list = $factory->createFromCompleteArray($app['config']['reputations']);
    } else {
        $list = [];
    }
    return $list;
});

/**
 * Registration of external tools
 */

/* URL generator */
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
