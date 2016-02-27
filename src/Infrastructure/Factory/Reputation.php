<?php

namespace Mikron\ReputationList\Infrastructure\Factory;
use Mikron\ReputationList\Domain\Blueprint\Calculator;
use Mikron\ReputationList\Domain\ValueObject\ReputationInfluence;

/**
 * Class Reputation
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
final class Reputation
{
    /**
     * @param \Mikron\ReputationList\Domain\ValueObject\ReputationNetwork $reputationNetwork
     * @param \Mikron\ReputationList\Domain\Entity\ReputationEvent[] $reputationEvents
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return \Mikron\ReputationList\Domain\Entity\Reputation
     */
    public function createFromParameters($reputationNetwork, $reputationEvents, $calculator, $initialStateOfCalculations)
    {
        return new \Mikron\ReputationList\Domain\Entity\Reputation(
            $reputationNetwork,
            $reputationEvents,
            $calculator,
            $initialStateOfCalculations
        );
    }

    /**
     * @param ReputationEvent[] $reputationEventsWild
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return \Mikron\ReputationList\Domain\Entity\Reputation[]
     */
    public function createFromReputationEvents($reputationEventsWild, $calculator, $initialStateOfCalculations)
    {
        return $this->createFromReputationEventsAndInfluences($reputationEventsWild, [], $calculator, $initialStateOfCalculations);
    }

    /**
     * @param ReputationEvent[] $reputationEventsWild
     * @param Calculator $calculator
     * @param int[] $initialStateOfCalculations
     * @return \Mikron\ReputationList\Domain\Entity\Reputation[]
     */
    public function createFromReputationEventsAndInfluences($reputationEventsWild, $reputationInfluences, $calculator, $initialStateOfCalculations)
    {
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
