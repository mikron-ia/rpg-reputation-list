<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Entity;
use Mikron\ReputationList\Domain\Exception\PersonNotFoundException;
use Mikron\ReputationList\Infrastructure\Storage\StorageForPerson;

/**
 * Class Person
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
final class Person
{
    /**
     * @param int $dbId
     * @param string $key
     * @param string $name
     * @param string $description
     * @param Entity\Reputation[] $reputations
     * @return Entity\Person
     */
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

    /**
     * Retrieves Person objects from database
     *
     * @param StorageEngine $connection
     * @return Entity\Person[]
     */
    public function retrieveAllFromDb($connection)
    {
        $personStorage = new StorageForPerson($connection);
        return $this->createFromCompleteArray($personStorage->retrieveAll());
    }

    /**
     * @param StorageEngine $connection
     * @param ReputationNetwork[] $reputationNetworksList
     * @param int $dbId
     * @return Entity\Person
     * @throws PersonNotFoundException
     */
    public function retrievePersonFromDbById($connection, $reputationNetworksList, $dbId)
    {
        $personStorage = new StorageForPerson($connection);
        $personWrapped = $personStorage->retrieveById($dbId);

        return $this->unwrapPerson($personWrapped, $connection, $reputationNetworksList);
    }

    /**
     * @param StorageEngine $connection
     * @param ReputationNetwork[] $reputationNetworksList
     * @param string $key
     * @return Entity\Person
     * @throws PersonNotFoundException
     */
    public function retrievePersonFromDbByKey($connection, $reputationNetworksList, $key)
    {
        $personStorage = new StorageForPerson($connection);
        $personWrapped = $personStorage->retrieveByKey($key);

        return $this->unwrapPerson($personWrapped, $connection, $reputationNetworksList);
    }

    /**
     * @param array $personWrapped
     * @param StorageEngine $connection
     * @param ReputationNetwork[] $reputationNetworksList
     * @return Entity\Person
     * @throws PersonNotFoundException
     */
    public function unwrapPerson($personWrapped, $connection, $reputationNetworksList)
    {
        if (!empty($personWrapped)) {
            $personUnwrapped = array_pop($personWrapped);

            $personDbId = $personUnwrapped['person_id'];

            $reputationEventsFactory = new ReputationEvent();
            $reputationFactory = new Reputation();

            $personReputationEvents = $reputationEventsFactory->retrieveReputationEventsForPersonFromDb(
                $connection,
                $reputationNetworksList,
                $personDbId
            );
            $personReputations = $reputationFactory->createFromReputationEvents($personReputationEvents);

            $person = $this->createFromSingleArray(
                $personUnwrapped['person_id'],
                $personUnwrapped['key'],
                $personUnwrapped['name'],
                $personUnwrapped['description'],
                $personReputations
            );

            return $person;
        } else {
            throw new PersonNotFoundException("Person with given ID has not been found in our database");
        }
    }
}
