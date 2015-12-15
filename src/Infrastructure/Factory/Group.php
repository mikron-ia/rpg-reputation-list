<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Entity;

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
}
