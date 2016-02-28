<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
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
     * @param ReputationEvent[] $reputationEvents
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return \Mikron\ReputationList\Domain\Entity\Reputation
     */
    public function createFromParameters(
        $reputationNetwork,
        $reputationEvents,
        $calculator,
        $initialStateOfCalculations
    ) {
        return new \Mikron\ReputationList\Domain\Entity\Reputation(
            $reputationNetwork,
            $reputationEvents,
            $calculator,
            $initialStateOfCalculations
        );
    }

    /**
     * @param ReputationEventEntity[] $reputationEventsWild
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return \Mikron\ReputationList\Domain\Entity\Reputation[]
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
     * @param ReputationInfluenceEntity[] $reputationInfluencesWild
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return \Mikron\ReputationList\Domain\Entity\Reputation[]
     */
    public function createFromReputationEventsAndInfluences(
        $reputationEventsWild,
        $reputationInfluencesWild,
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

        $reputationFactory = new Reputation();

        foreach ($reputationEventsOrdered as $reputationEventsCategory) {
            $reputationNetwork = $reputationEventsCategory[0]->getReputationNetwork();

            $reputation = $reputationFactory->createFromParameters(
                $reputationNetwork,
                $reputationEventsCategory,
                $calculator,
                $initialStateOfCalculations
            );
            $reputations[$reputation->getReputationNetwork()->getCode()] = $reputation;
        }

        return $reputations;
    }
}
