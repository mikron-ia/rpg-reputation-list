<?php

$app['networks'] = $app->share(function ($app) {

    if (isset($app['config.system.reputations'])) {
        $factory = new \Mikron\ReputationList\Infrastructure\Factory\ReputationNetwork();

        $networks = $app['config.system.reputations'];

        $list = $factory->createFromCompleteArray($app['config.system.reputations']);
    } else {
        $list = [];
    }

    return $list;
});

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
