<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;
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
     * @throws ExceptionWithSafeMessage
     * @throws MissingComponentException
     */
    public function __construct($config)
    {
        if (!isset($config['dbEngine'])) {
            throw new MissingComponentException("Missing database engine setting");
        }
        $dbEngine = $config['dbEngine'];

        $dbReference = $config['databaseReference'];

        if (!isset($dbReference[$dbEngine])) {
            throw new MissingComponentException(
                "Missing database reference",
                "Missing database reference for " . $dbEngine
            );
        }

        $dbClass = '\Mikron\ReputationList\Infrastructure\Storage\\' . $dbReference[$dbEngine] . 'StorageEngine';

        if (!class_exists($dbClass)) {
            throw new MissingComponentException(
                "Missing or incorrect database class",
                "Missing database class for " . $dbEngine . ". Tried to load " . $dbClass . "."
            );
        }

        try {
            $this->connection = new $dbClass($config[$dbEngine]);
        } catch (\Exception $e) {
            throw new ExceptionWithSafeMessage(
                "Database connection error",
                "Database connection error: " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @return StorageEngine
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
