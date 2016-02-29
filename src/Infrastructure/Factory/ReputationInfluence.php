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
     * @param int $divisor
     * @return ReputationInfluenceEntity
     */
    public function createFromSingleArray(ReputationNetworkEntity $reputationNetwork, $change, $divisor)
    {
        return new ReputationInfluenceEntity($reputationNetwork, $change, $divisor);
    }

    /**
     * @param ReputationEntity[][] $membersReputations
     * @param int $weightedMemberCount
     * @return ReputationInfluenceEntity[]
     */
    public function createFromMemberReputation($membersReputations, $weightedMemberCount)
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
                    $memberInfluenceExtended,
                    $weightedMemberCount <> 0 ? $weightedMemberCount : 1
                );

                $reputationInfluences[$repCode][] = $reputationInfluence;
            }
        }
        return $reputationInfluences;
    }
}
