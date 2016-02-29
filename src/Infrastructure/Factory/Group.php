<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
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
     * @param Calculator $calculatorForPerson
     * @param Calculator $calculatorForGroup
     * @return Entity\Group
     * @throws GroupNotFoundException
     */
    public function retrieveGroupFromDbByKey(
        $connection,
        $logger,
        $reputationNetworksList,
        $key,
        $calculatorForPerson,
        $calculatorForGroup
    ) {
        $groupStorage = new StorageForGroup($connection);
        $groupWrapped = $groupStorage->retrieveByKey($key);

        return $this->unwrapGroup($groupWrapped, $connection, $logger, $reputationNetworksList, $calculatorForPerson,
            $calculatorForGroup);
    }

    /**
     * @param array $groupWrapped
     * @param StorageEngine $connection
     * @param LoggerInterface $logger
     * @param ReputationNetwork[] $reputationNetworksList
     * @param Calculator $calculatorForPerson
     * @param Calculator $calculatorForGroup
     * @return Entity\Group
     * @throws GroupNotFoundException
     */
    public function unwrapGroup(
        $groupWrapped,
        $connection,
        $logger,
        $reputationNetworksList,
        $calculatorForPerson,
        $calculatorForGroup
    ) {
        if (!empty($groupWrapped)) {
            $groupUnwrapped = array_pop($groupWrapped);

            $groupDbId = $groupUnwrapped['group_id'];

            $personFactory = new Person();

            $members = $personFactory->retrievePersonForGroupFromDb(
                $connection,
                $logger,
                $reputationNetworksList,
                $calculatorForPerson,
                [],
                $groupDbId
            );

            /* Extract member influences and calculate them properly */
            $membersReputations = [];
            foreach ($members as $member) {
                /** @var Entity\Person $member */
                $membersReputations[] = $member->getReputations();
            }

            $memberCount = count($members);

            $reputationInfluencesFactory = new ReputationInfluence();
            $groupReputationInfluences = $reputationInfluencesFactory->createFromMemberReputation($membersReputations, $memberCount);

            /* Create data specifically for the group - events & reputations */
            $reputationEventsFactory = new ReputationEvent();
            $reputationFactory = new Reputation();

            $reputationInitialPattern = [];

            $groupReputationEvents = $reputationEventsFactory->retrieveReputationEventsForPersonFromDb(
                $connection,
                $logger,
                $reputationNetworksList,
                $groupDbId
            );
            $groupReputations = $reputationFactory->createFromReputationEventsAndInfluences(
                $groupReputationEvents,
                $groupReputationInfluences,
                $calculatorForGroup,
                $reputationInitialPattern
            );

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
