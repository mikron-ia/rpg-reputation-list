<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class MySqlStorage
 * @package Mikron\ReputationList\Infrastructure\Storage
 */
final class MySqlStorageEngine implements StorageEngine
{
    private $connection;

    /**
     * @param $dbConfig
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __construct($dbConfig)
    {
        try {
            $config = new Configuration();

            $this->connection = DriverManager::getConnection($dbConfig, $config);
        } catch (\PDOException $e) {
            throw new Exception('Could not connect to MySQL database: ' . $e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $primaryKeyName
     * @param $primaryKeyValues
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function selectByPrimaryKey($table, $primaryKeyName, $primaryKeyValues)
    {
        $basicSql = "SELECT `$primaryKeyName` AS `dbId`, `name`, `description` FROM `$table`";

        if (!empty($primaryKeyValues)) {
            $where = " WHERE $primaryKeyName IN (?)";
            $statement = $this->connection->executeQuery($basicSql . $where,
                [$primaryKeyValues],
                [Connection::PARAM_INT_ARRAY]);
        } else {
            $statement = $this->connection->executeQuery($basicSql);
        }

        return $statement->fetchAll((\PDO::FETCH_ASSOC));
    }

    /**
     * @param $table
     * @param $primaryKeyName
     * @return array
     */
    public function selectAll($table, $primaryKeyName)
    {
        return $this->selectByPrimaryKey($table, $primaryKeyName, []);
    }
}
