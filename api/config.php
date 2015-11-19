<?php

use Mikron\ReputationList\Domain\Exception\MissingComponentException;

$configPath = __DIR__ . '/../config/';

/* Level 0: main config */
$app['config.main'] = require($configPath . 'main.php');

/* Level 2: Specific story / epic; loaded first, as it determines choice of Level 1 config option */
if (file_exists($configPath . 'epic.php')) {
    $app['config.epic'] = require($configPath . 'epic.php');
}

/* Level 1: RPG system data; selected in epic */
if (isset($app['config.epic']['system'])) {
    $path = $configPath . 'data/' . $app['config.epic']['system'] . '.php';
    if (file_exists($path)) {
        $app['config.system'] = require($path);
    } else {
        throw new MissingComponentException('Data file for system coded "' . $app['config.epic']['system']
            . '" not found"');
    }
}

/* Level 3: DB connectivity and other per-deployment issues */
if (file_exists($configPath . 'deployment.php')) {
    $app['config.deploy'] = require($configPath . 'deployment.php');
}

