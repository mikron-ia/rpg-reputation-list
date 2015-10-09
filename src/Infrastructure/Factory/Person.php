<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Mikron\ReputationList\Domain\Entity;
use Mikron\ReputationList\Domain\Exception\PersonNotFoundException;
use Mikron\ReputationList\Infrastructure\Storage\StorageForPerson;

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
                $list[] = $this->createFromSingleArray($record['person_id'], $record['name'], $record['description'], []);
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
        $personStorage = new StorageForPerson($connection);

        $array = $personStorage->retrieveAll();

        if (!empty($array)) {
            foreach ($array as $record) {
                $list[] = $this->createFromSingleArray($record['person_id'], $record['name'], $record['description'], []);
            }
        }

        return $list;
    }

    public function retrievePersonFromDb($connection, $reputationNetworksList, $dbId)
    {
        $personStorage = new StorageForPerson($connection);

        $personWrapped = $personStorage->retrieve($dbId);

        if (!empty($personWrapped)) {
            $personUnwrapped = array_pop($personWrapped);

            $reputationEventsFactory = new ReputationEvent();
            $reputationFactory = new Reputation();

            $personReputationEvents = $reputationEventsFactory->retrieveReputationEventsForPersonFromDb($connection, $reputationNetworksList, $dbId);
            $personReputations = $reputationFactory->createFromReputationEvents($personReputationEvents);

            $person = $this->createFromSingleArray($personUnwrapped['person_id'], $personUnwrapped['name'], $personUnwrapped['description'], $personReputations);
        } else {
            throw new PersonNotFoundException("Person with given ID has not been found in our database");
        }

        return $person;
    }
}