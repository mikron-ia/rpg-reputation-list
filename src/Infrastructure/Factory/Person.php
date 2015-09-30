<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Entity;

class Person
{
    public function createFromSingleArray($dbId, $name, $reputations)
    {
        return new Entity\Person($dbId, $name, $reputations);
    }

    /**
     * Creates Person objects from array
     * @param $array
     * @return Person[]
     */
    public function createFromCompleteArray($array)
    {
        $list = [];

        if (!empty($array)) {
            foreach ($array as $record) {
                $list[] = $this->createFromSingleArray($record['dbId'], $record['name'], []);
            }
        }

        return $list;
    }

    /**
     * Retrieves Person objects from database
     *
     * @param $connection
     * @return array
     */
    public function retrieveAllFromDb($connection)
    {
        $personStorage = new \Mikron\ReputationList\Infrastructure\Storage\MySqlPerson($connection);

        $array = $personStorage->retrieveAll();

        if (!empty($array)) {
            foreach ($array as $record) {
                $list[] = $this->createFromSingleArray($record['dbId'], $record['name'], []);
            }
        }

        return $list;
    }

    public function retrievePersonFromDb($connection, $dbId)
    {
        $personStorage = new \Mikron\ReputationList\Infrastructure\Storage\MySqlPerson($connection);

        $result = $personStorage->retrieve($dbId);

        if (!empty($result)) {
            $person = $this->createFromSingleArray($result['dbId'], $result['name'], []);
        } else {
            $person = null;
        }

        return $person;
    }
}