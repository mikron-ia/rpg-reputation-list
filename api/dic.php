<?php

/**
 * Inter-application dependency injections
 */

/* Reputation networks */
$app['networks'] = $app->share(function ($app) {
    if (isset($app['config']['systemData']['reputations'])) {
        $factory = new \Mikron\ReputationList\Infrastructure\Factory\ReputationNetwork();
        $list = $factory->createFromCompleteArray($app['config']['systemData']['reputations']);
    } else {
        throw new \Mikron\ReputationList\Domain\Exception\MissingComponentException(
            "Reputation table missing from configuration"
        );
    }
    return $list;
});

/**
 * @todo DI for all acceptable authentication strategies
 */
$app['authenticationTokensForHub'] = $app->share(function ($app) {
    return null;
});

$app['authenticationTokensForManager'] = $app->share(function ($app) {
    return null;
});

/**
 * Registration of external tools
 */

/* URL generator */
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
