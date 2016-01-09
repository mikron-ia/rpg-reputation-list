<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Entity;
use Mikron\ReputationList\Domain\Exception\GroupNotFoundException;
use Mikron\ReputationList\Infrastructure\Storage\StorageForGroup;
use Psr\Log\LoggerInterface;

class Group
{
    /**
     * @param int $dbId
     * @param string $key
     * @param string $name
     * @param string $description
     * @param Entity\Reputation[] $reputations
     * @param Entity\ReputationEvent[] $reputationEvents
     * @param Entity\Person[] $members
     * @return Entity\Group
     */
    public function createFromSingleArray(
        $dbId,
        $key,
        $name,
        $description,
        array $reputations,
        array $reputationEvents,
        array $members
    ) {
        $idFactory = new StorageIdentification();
        $identification = $idFactory->createFromData($dbId, $key);

        return new Entity\Group(
            $identification,
            $name,
            $description,
            $reputations,
            $reputationEvents,
            $members
        );
    }

    /**
     * @param StorageEngine $connection
     * @param LoggerInterface $logger
     * @param ReputationNetwork[] $reputationNetworksList
     * @param string $key
     * @param string[] $methodsToCalculate
     * @return Entity\Group
     * @throws GroupNotFoundException
     */
    public function retrieveGroupFromDbByKey($connection, $logger, $reputationNetworksList, $key, $methodsToCalculate)
    {
        $groupStorage = new StorageForGroup($connection);
        $groupWrapped = $groupStorage->retrieveByKey($key);

        return $this->unwrapGroup($groupWrapped, $connection, $logger, $reputationNetworksList, $methodsToCalculate);
    }

    /**
     * @param array $groupWrapped
     * @param StorageEngine $connection
     * @param LoggerInterface $logger
     * @param ReputationNetwork[] $reputationNetworksList
     * @param string[] $methodsToCalculate
     * @return Entity\Group
     * @throws GroupNotFoundException
     */
    public function unwrapGroup($groupWrapped, $connection, $logger, $reputationNetworksList, $methodsToCalculate)
    {
        if (!empty($groupWrapped)) {
            $groupUnwrapped = array_pop($groupWrapped);

            $groupDbId = $groupUnwrapped['group_id'];

            $personFactory = new Person();

            $members = $personFactory->retrievePersonForGroupFromDb(
                $connection,
                $logger,
                $reputationNetworksList,
                $methodsToCalculate,
                $groupDbId
            );

            $reputationEventsFactory = new ReputationEvent();
            $reputationFactory = new Reputation();

            $groupReputationEvents = $reputationEventsFactory->retrieveReputationEventsForPersonFromDb(
                $connection,
                $logger,
                $reputationNetworksList,
                $groupDbId
            );
            $groupReputations = $reputationFactory->createFromReputationEvents($groupReputationEvents, $methodsToCalculate, []);

            $group = $this->createFromSingleArray(
                $groupUnwrapped['group_id'],
                $groupUnwrapped['key'],
                $groupUnwrapped['name'],
                $groupUnwrapped['description'],
                $groupReputations,
                $groupReputationEvents,
                $members
            );

            return $group;
        } else {
            throw new GroupNotFoundException("Group with given ID has not been found in our database");
        }
    }
}
