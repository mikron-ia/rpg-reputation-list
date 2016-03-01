<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
use Mikron\ReputationList\Domain\Entity\Reputation as ReputationEntity;
use Mikron\ReputationList\Domain\Entity\ReputationEvent as ReputationEventEntity;
use Mikron\ReputationList\Domain\ValueObject\ReputationInfluence as ReputationInfluenceEntity;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork as ReputationNetworkEntity;

/**
 * Class Reputation
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
final class Reputation
{
    /**
     * @param ReputationNetworkEntity $reputationNetwork
     * @param ReputationEventEntity[] $reputationEvents
     * @param ReputationInfluenceEntity[] $reputationInfluences
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return ReputationEntity
     */
    public function createFromParameters(
        $reputationNetwork,
        $reputationEvents,
        $reputationInfluences,
        $calculator,
        $initialStateOfCalculations
    ) {
        return new ReputationEntity(
            $reputationNetwork,
            $reputationEvents,
            $reputationInfluences,
            $calculator,
            $initialStateOfCalculations
        );
    }

    /**
     * @param ReputationEventEntity[] $reputationEventsWild
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return ReputationEntity[]
     */
    public function createFromReputationEvents(
        $reputationEventsWild,
        $calculator,
        $initialStateOfCalculations
    ) {
        return $this->createFromReputationEventsAndInfluences(
            $reputationEventsWild,
            [],
            $calculator,
            $initialStateOfCalculations
        );
    }

    /**
     * @param ReputationEventEntity[] $reputationEventsWild
     * @param ReputationInfluenceEntity[] $reputationInfluences
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return ReputationEntity[]
     */
    public function createFromReputationEventsAndInfluences(
        $reputationEventsWild,
        $reputationInfluences,
        $calculator,
        $initialStateOfCalculations
    ) {
        $reputationEventsOrdered = [];

        foreach ($reputationEventsWild as $reputationEvent) {
            $reputationEventRepCode = $reputationEvent->getReputationNetwork()->getCode();

            if (!isset($reputationEventsOrdered[$reputationEventRepCode])) {
                $reputationEventsOrdered[$reputationEventRepCode] = [];
            }

            $reputationEventsOrdered[$reputationEventRepCode][] = $reputationEvent;
        }

        $reputations = [];

        foreach ($reputationEventsOrdered as $reputationCode => $reputationEventsCategory) {
            $reputationNetwork = $reputationEventsCategory[0]->getReputationNetwork();

            if(isset($reputationInfluences[$reputationCode])) {
                $reputationInfluencesForThisReputation = $reputationInfluences[$reputationCode];
                unset($reputationInfluences[$reputationCode]);
            } else {
                $reputationInfluencesForThisReputation = [];
            }

            $reputation = $this->createFromParameters(
                $reputationNetwork,
                $reputationEventsCategory,
                $reputationInfluencesForThisReputation,
                $calculator,
                $initialStateOfCalculations
            );
            $reputations[$reputationCode] = $reputation;
        }

        /* This loop serves reputations that are influence-only - ie. do not have events */
        foreach ($reputationInfluences as $reputationCode => $reputationInfluenceCategory) {
            $reputationNetwork = $reputationInfluenceCategory[0]->getReputationNetwork();

            $reputation = $this->createFromParameters(
                $reputationNetwork,
                [],
                $reputationInfluenceCategory,
                $calculator,
                $initialStateOfCalculations
            );
            $reputations[$reputationCode] = $reputation;
        }

        return $reputations;
    }
}
