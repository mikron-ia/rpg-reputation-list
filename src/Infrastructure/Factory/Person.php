<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Entity;

class Person
{
    public function createFromSingleArray($dbId, $name, $description, $reputations)
    {
        return new Entity\Person($dbId, $name, $description, $reputations);
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
                $list[] = $this->createFromSingleArray($record['dbId'], $record['name'], "[no description]", []);
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
                $list[] = $this->createFromSingleArray($record['dbId'], $record['name'], "[no description]", []);
            }
        }

        return $list;
    }

    public function retrievePersonFromDb($connection, $dbId)
    {
        $personStorage = new \Mikron\ReputationList\Infrastructure\Storage\MySqlPerson($connection);

        $personWrapped = $personStorage->retrieve($dbId);

        if (!empty($personWrapped)) {
            $personUnwrapped = array_pop($personWrapped);
            $person = $this->createFromSingleArray($personUnwrapped['dbId'], $personUnwrapped['name'], "[no description]", []);
        } else {
            $person = null;
        }

        return $person;
    }
}