<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Entity\Reputation as ReputationEntity;
use Mikron\ReputationList\Domain\ValueObject\ReputationInfluence as ReputationInfluenceEntity;
use Mikron\ReputationList\Domain\Exception\RecordNotFoundException;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork as ReputationNetworkEntity;

/**
 * Class ReputationEvent
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
final class ReputationInfluence
{
    /**
     * @param ReputationNetworkEntity $reputationNetwork
     * @param int $change
     * @return ReputationInfluenceEntity
     * @throws RecordNotFoundException
     */
    public function createFromSingleArray(ReputationNetworkEntity $reputationNetwork, $change)
    {
        return new ReputationInfluenceEntity($reputationNetwork, $change);
    }

    /**
     * @param ReputationEntity[][] $membersReputations
     * @return ReputationInfluenceEntity[]
     */
    public function createFromMemberReputation($membersReputations)
    {
        $reputationInfluences = [];

        foreach ($membersReputations as $memberReputations) {
            foreach ($memberReputations as $repCode => $memberReputation) {
                if (!isset($reputationInfluences[$repCode])) {
                    $reputationInfluences[$repCode] = [];
                }

                $memberReputations = $memberReputation->getValues([]);
                $memberInfluenceExtended = isset($memberReputations['influence']) ? $memberReputations['influence'] : 0;

                $reputationInfluence = $this->createFromSingleArray(
                    $memberReputation->getReputationNetwork(),
                    $memberInfluenceExtended
                );

                $reputationInfluences[$repCode][] = $reputationInfluence;
            }
        }
        return $reputationInfluences;
    }
}
