<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class MySqlStorage
 * @package Mikron\ReputationList\Infrastructure\Storage
 */
final class MySqlStorageEngine implements StorageEngine
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param $table
     * @param $primaryKeyName
     * @param $keyName
     * @param $keyValues
     * @return array
     * @throws ExceptionWithSafeMessage
     */
    public function selectByKey($table, $primaryKeyName, $keyName, $keyValues)
    {
        $basicSql = "SELECT * FROM `$table`";

        try {
            if (!empty($keyValues)) {
                $where = " WHERE `$keyName` IN (?)";
                $statement = $this->connection->executeQuery($basicSql . $where,
                    [$keyValues],
                    [Connection::PARAM_STR_ARRAY]
                );
            } else {
                $statement = $this->connection->executeQuery($basicSql);
            }
        } catch (\Exception $e) {
            throw new ExceptionWithSafeMessage(
                'Database connection error',
                'Database connection error: ' . $e->getMessage()
            );
        }

        return $statement->fetchAll((\PDO::FETCH_ASSOC));
    }

    /**
     * @param $dbConfig
     * @throws DBALException
     */
    public function __construct($dbConfig)
    {
        try {
            $config = new Configuration();
            $this->connection = DriverManager::getConnection($dbConfig, $config);
            $this->connection->executeQuery('SELECT 0');
        } catch (\Exception $e) {
            throw new Exception('Database connection test failed: ' . $e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $primaryKeyName
     * @return array
     */
    public function selectAll($table, $primaryKeyName)
    {
        return $this->selectByKey($table, $primaryKeyName, null, []);
    }

    public function selectByPrimaryKey($table, $primaryKeyName, $primaryKeyValues)
    {
        return $this->selectByKey($table, $primaryKeyName, $primaryKeyName, $primaryKeyValues);
    }

    public function selectViaAssociation($tableToSelectFrom, $associationTable, $primaryKeyName, $keyName, $keyValues)
    {
        $basicSql = "SELECT * FROM `$tableToSelectFrom` JOIN `$associationTable` USING ($primaryKeyName)";

        try {
            if (!empty($keyValues)) {
                $where = " WHERE `$keyName` IN (?)";
                $statement = $this->connection->executeQuery($basicSql . $where,
                    [$keyValues],
                    [Connection::PARAM_STR_ARRAY]
                );
            } else {
                $statement = $this->connection->executeQuery($basicSql);
            }
        } catch (\Exception $e) {
            throw new ExceptionWithSafeMessage(
                'Database connection error',
                'Database connection error: ' . $e->getMessage()
            );
        }

        return $statement->fetchAll((\PDO::FETCH_ASSOC));
    }

    public function countViaAssociation($tableToSelectFrom, $associationTable, $primaryKeyName, $keyName, $keyValues)
    {
        $basicSql = "SELECT * FROM `$tableToSelectFrom` JOIN `$associationTable` USING ($primaryKeyName)";

        try {
            if (!empty($keyValues)) {
                $where = " WHERE `$keyName` IN (?)";
                $statement = $this->connection->executeQuery($basicSql . $where,
                    [$keyValues],
                    [Connection::PARAM_STR_ARRAY]
                );
            } else {
                $statement = $this->connection->executeQuery($basicSql);
            }
        } catch (\Exception $e) {
            throw new ExceptionWithSafeMessage(
                'Database connection error',
                'Database connection error: ' . $e->getMessage()
            );
        }

        return $statement->rowCount();
    }
}
