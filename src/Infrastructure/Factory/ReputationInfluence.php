<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\ValueObject\ReputationInfluence as ReputationInfluenceEntity;
use Mikron\ReputationList\Domain\Exception\RecordNotFoundException;

/**
 * Class ReputationEvent
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
final class ReputationInfluence
{
    /**
     * @param $reputationNetworksList
     * @param string $reputationNetworkCode
     * @param int $change
     * @return ReputationInfluenceEntity
     * @throws RecordNotFoundException
     */
    public function createFromSingleArray(
        $reputationNetworksList,
        $reputationNetworkCode,
        $change
    ) {
        if (isset($reputationNetworksList[$reputationNetworkCode])) {
            $reputationNetwork = $reputationNetworksList[$reputationNetworkCode];
        } else {
            throw new RecordNotFoundException(
                "Reputation not found",
                "Unrecognised reputation code: " . $reputationNetworkCode
            );
        }

        return new ReputationInfluenceEntity($reputationNetwork, $change);
    }

    /**
     * @param Reputation[][] $reputations
     * @return ReputationInfluenceEntity[]
     */
    public function createFromMemberReputation($reputations)
    {
        return [];
    }
}
