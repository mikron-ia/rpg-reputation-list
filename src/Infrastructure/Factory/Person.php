<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Mikron\ReputationList\Domain\Entity;
use Mikron\ReputationList\Domain\Exception\PersonNotFoundException;

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
        $personStorage = new \Mikron\ReputationList\Infrastructure\Storage\StorageForPerson($connection);

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
        $personStorage = new \Mikron\ReputationList\Infrastructure\Storage\StorageForPerson($connection);

        $personWrapped = $personStorage->retrieve($dbId);

        if (!empty($personWrapped)) {
            $personUnwrapped = array_pop($personWrapped);
            $person = $this->createFromSingleArray($personUnwrapped['dbId'], $personUnwrapped['name'], $personUnwrapped['description'], []);
        } else {
            throw new PersonNotFoundException("Person with ID of $dbId has not been found in our database");
        }

        return $person;
    }
}