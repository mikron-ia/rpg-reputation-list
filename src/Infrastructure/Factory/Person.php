<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Entity;
use Mikron\ReputationList\Domain\Exception\PersonNotFoundException;
use Mikron\ReputationList\Infrastructure\Storage\StorageForPerson;
use Psr\Log\LoggerInterface;

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
     * @param Entity\ReputationEvent[] $reputationEvents
     * @param int $weight
     * @return Entity\Person
     */
    public function createFromSingleArray($dbId, $key, $name, $description, array $reputations, array $reputationEvents, $weight)
    {
        $idFactory = new StorageIdentification();
        $identification = $idFactory->createFromData($dbId, $key);

        return new Entity\Person(
            $identification,
            $name,
            $description,
            $reputations,
            $reputationEvents,
            $weight
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
                    [],
                    [],
                    10
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
     * @param LoggerInterface $logger
     * @param ReputationNetwork[] $reputationNetworksList
     * @param int $dbId
     * @param Calculator $calculator
     * @return Entity\Person
     * @throws PersonNotFoundException
     */
    public function retrievePersonFromDbById($connection, $logger, $reputationNetworksList, $dbId, $calculator)
    {
        $personStorage = new StorageForPerson($connection);
        $personWrapped = $personStorage->retrieveById($dbId);

        return $this->unwrapPerson(
            $personWrapped,
            $connection,
            $logger,
            $reputationNetworksList,
            $calculator,
            []
        );
    }

    /**
     * @param StorageEngine $connection
     * @param LoggerInterface $logger
     * @param ReputationNetwork[] $reputationNetworksList
     * @param string $key
     * @param Calculator $calculator
     * @return Entity\Person
     * @throws PersonNotFoundException
     */
    public function retrievePersonFromDbByKey($connection, $logger, $reputationNetworksList, $key, $calculator)
    {
        $personStorage = new StorageForPerson($connection);
        $personWrapped = $personStorage->retrieveByKey($key);

        return $this->unwrapPerson(
            $personWrapped,
            $connection,
            $logger,
            $reputationNetworksList,
            $calculator,
            []
        );
    }

    /**
     * @param StorageEngine $connection
     * @param LoggerInterface $logger
     * @param ReputationNetwork[] $reputationNetworksList
     * @param Calculator $calculator
     * @param int[] $reputationInitialPattern
     * @param int $groupId
     * @return Person[]
     * @throws PersonNotFoundException
     * @todo Rework unwrapper - it has to be used here, as createFromCompleteArray() does not load reputations
     */
    public function retrievePersonForGroupFromDb(
        $connection,
        $logger,
        $reputationNetworksList,
        $calculator,
        $reputationInitialPattern,
        $groupId
    ) {
        $personStorage = new StorageForPerson($connection);
        $personsRetrieved = $personStorage->retrieveByGroup($groupId);

        $persons = [];

        foreach ($personsRetrieved as $personRetrieved) {
            $persons[] = $this->unwrapPerson(
                [$personRetrieved],
                $connection,
                $logger,
                $reputationNetworksList,
                $calculator,
                $reputationInitialPattern
            );
        }

        return $persons;
    }

    /**
     * @param array $personWrapped
     * @param StorageEngine $connection
     * @param LoggerInterface $logger
     * @param ReputationNetwork[] $reputationNetworksList
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return Entity\Person
     * @throws PersonNotFoundException
     */
    public function unwrapPerson(
        $personWrapped,
        $connection,
        $logger,
        $reputationNetworksList,
        $calculator,
        $initialStateOfCalculations
    ) {
        if (!empty($personWrapped)) {
            $personUnwrapped = array_pop($personWrapped);

            $personDbId = $personUnwrapped['person_id'];

            $reputationEventsFactory = new ReputationEvent();
            $reputationFactory = new Reputation();

            $personReputationEvents = $reputationEventsFactory->retrieveReputationEventsForPersonFromDb(
                $connection,
                $logger,
                $reputationNetworksList,
                $personDbId
            );
            $personReputations = $reputationFactory->createFromReputationEvents(
                $personReputationEvents,
                $calculator,
                $initialStateOfCalculations
            );

            $person = $this->createFromSingleArray(
                $personUnwrapped['person_id'],
                $personUnwrapped['key'],
                $personUnwrapped['name'],
                $personUnwrapped['description'],
                $personReputations,
                $personReputationEvents,
                $personUnwrapped['weight']
            );

            return $person;
        } else {
            throw new PersonNotFoundException("Person with given ID has not been found in our database");
        }
    }

    public function countPeopleByGroup($connection, $groupId)
    {
        $personStorage = new StorageForPerson($connection);
        $personCount = $personStorage->countByGroup($groupId);

        return $personCount;
    }
}
