<?php

use Mikron\ReputationList\Domain\Exception\MissingComponentException;

$configPath = __DIR__ . '/../config/';

/* Level 0: main config */
require_once $configPath . 'main.php';

/* Level 2: Specific story / epic; loaded first, as it determines choice of Level 1 config option */
if (file_exists($configPath . 'epic.php')) {
    require_once $configPath . 'epic.php';
}

/* Level 1: RPG system data; selected in epic */
if (isset($epicConfig['system'])) {
    $path = $configPath . 'data/' . $epicConfig['system'] . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        throw new MissingComponentException('Data file for system coded "' . $epicConfig['system'] . '" not found"');
    }
}

/* Level 3: DB connectivity and other per-deployment issues */
if (file_exists($configPath . 'deployment.php')) {
    require_once $configPath . 'deployment.php';
}
