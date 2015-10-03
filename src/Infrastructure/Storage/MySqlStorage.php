<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Symfony\Component\Config\Definition\Exception\Exception;

final class MySqlStorage
{
    private $connection;

    public function __construct($url, $username, $password, $dbname, array $options = [])
    {
        $dsn = "mysql:host=$url;dbname=$dbname";

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
                $rows[] = "$field = '$value'";
            }
            $where = ' WHERE ' . implode(' AND ', $rows);
        } else {
            $where = "";
        }

        $result = $this->connection->query("SELECT `person_id` AS `dbId`, `name`, `description` FROM `$table`" . $where);

        return $result->fetchAll((\PDO::FETCH_ASSOC));
    }
}
