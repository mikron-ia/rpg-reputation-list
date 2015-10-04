<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Symfony\Component\Config\Definition\Exception\Exception;

final class MySqlStorage
{
    private $connection;

    public function __construct($url, $username, $password, $dbName, array $options = [])
    {
        $dsn = "mysql:host=$url;dbname=$dbName";

        try {
            $this->connection = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new Exception('Could not connect to MySQL database: ' . $e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $whereArray
     * @return array
     */
    public function simpleSelect($table, $whereArray)
    {
        if (!empty($whereArray)) {
            $rows = [];
            foreach ($whereArray as $field => $value) {
                $rows[] = "$field = :$field";
            }
            $where = ' WHERE ' . implode(' AND ', $rows);
        } else {
            $where = "";
        }

        $statement = $this->connection->prepare("SELECT `person_id` AS `dbId`, `name`, `description` FROM `$table`" . $where);

        if (!empty($whereArray)) {
            foreach ($whereArray as $field => $value) {
                $statement->bindValue($field, $value);
            }
        }

        $statement->execute();

        return $statement->fetchAll((\PDO::FETCH_ASSOC));
    }
}
