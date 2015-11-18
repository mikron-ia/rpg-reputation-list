<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Entity;
use Mikron\ReputationList\Domain\Exception\PersonNotFoundException;
use Mikron\ReputationList\Infrastructure\Storage\StorageForPerson;

class Person
{
    public function createFromSingleArray($dbId, $key, $name, $description, $reputations)
    {
        $idFactory = new StorageIdentification();
        $identification = $idFactory->createFromData($dbId, $key);

        return new Entity\Person(
            $identification,
            $name,
            $description,
            $reputations
        );
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
                $list[] = $this->createFromSingleArray($record['person_id'], $record['key'], $record['name'], $record['description'], []);
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
                $list[] = $this->createFromSingleArray(
                    $record['person_id'],
                    $record['key'],
                    $record['name'],
                    $record['description'],
                    []
                );
            }
        }

        return $list;
    }

    public function retrievePersonFromDbById($connection, $reputationNetworksList, $dbId)
    {
        $personStorage = new StorageForPerson($connection);

        $personWrapped = $personStorage->retrieveById($dbId);

        if (!empty($personWrapped)) {
            $personUnwrapped = array_pop($personWrapped);

            $personDbId = $personUnwrapped['person_id'];

            $reputationEventsFactory = new ReputationEvent();
            $reputationFactory = new Reputation();

            $personReputationEvents = $reputationEventsFactory->retrieveReputationEventsForPersonFromDb($connection, $reputationNetworksList, $personDbId);
            $personReputations = $reputationFactory->createFromReputationEvents($personReputationEvents);

            $person = $this->createFromSingleArray(
                $personUnwrapped['person_id'],
                $personUnwrapped['key'],
                $personUnwrapped['name'],
                $personUnwrapped['description'],
                $personReputations
            );
        } else {
            throw new PersonNotFoundException("Person with given ID has not been found in our database");
        }

        return $person;
    }

    public function retrievePersonFromDbByKey($connection, $reputationNetworksList, $key)
    {
        $personStorage = new StorageForPerson($connection);

        $personWrapped = $personStorage->retrieveByKey($key);

        if (!empty($personWrapped)) {
            $personUnwrapped = array_pop($personWrapped);

            $personDbId = $personUnwrapped['person_id'];

            $reputationEventsFactory = new ReputationEvent();
            $reputationFactory = new Reputation();

            $personReputationEvents = $reputationEventsFactory->retrieveReputationEventsForPersonFromDb($connection, $reputationNetworksList, $personDbId);
            $personReputations = $reputationFactory->createFromReputationEvents($personReputationEvents);

            $person = $this->createFromSingleArray(
                $personUnwrapped['person_id'],
                $personUnwrapped['key'],
                $personUnwrapped['name'],
                $personUnwrapped['description'],
                $personReputations
            );
        } else {
            throw new PersonNotFoundException("Person with given ID has not been found in our database");
        }

        return $person;
    }
}
