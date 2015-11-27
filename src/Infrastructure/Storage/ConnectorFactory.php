<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Exception\MissingComponentException;

/**
 * Class ConnectorFactory
 * @package Mikron\ReputationList\Infrastructure\Storage
 */
class ConnectorFactory
{
    /**
     * @var StorageEngine
     */
    private $connection;

    /**
     * ConnectorFactory constructor.
     * @param $config
     * @throws MissingComponentException
     */
    public function __construct($config)
    {
        if (!isset($config['dbEngine'])) {
            throw new MissingComponentException("Missing database engine");
        }
        $dbEngine = $config['dbEngine'];

        $dbReference = $config['databaseReference'];

        $dbClass = '\Mikron\ReputationList\Infrastructure\Storage\\' . $dbReference[$dbEngine] . 'StorageEngine';

        if (!class_exists($dbClass)) {
            throw new MissingComponentException("Missing class");
        }

        $this->connection = new $dbClass($config[$dbEngine]);
    }

    /**
     * @return StorageEngine
     */
    public function getConnection()
    {
        return $this->connection;
    }

}
