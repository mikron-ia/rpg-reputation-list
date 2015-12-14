<?php

/**
 * Registration of external tools
 */

/* URL generator */
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

/* Logging system */
$app->register(new \Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => $app['config']['logging']['logfile'],
    'monolog.name' => $app['config']['logging']['projectNameForLogging'],
    'monolog.level' => $app['config']['debug'] ? $app['config']['logging']['logLevel'] : $app['config']['logging']['logLevelDebug'],
));

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
