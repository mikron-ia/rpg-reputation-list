<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

/**
 * Class Reputation
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
final class Reputation
{
    /**
     * @param \Mikron\ReputationList\Domain\ValueObject\ReputationNetwork $reputationNetwork
     * @param \Mikron\ReputationList\Domain\Entity\ReputationEvent[] $reputationEvents
     * @param string[] $methodsToCalculate
     * @param int[] $initialStateOfCalculations
     * @return \Mikron\ReputationList\Domain\Entity\Reputation
     */
    public function createFromParameters($reputationNetwork, $reputationEvents, $methodsToCalculate, $initialStateOfCalculations)
    {
        return new \Mikron\ReputationList\Domain\Entity\Reputation(
            $reputationNetwork,
            $reputationEvents,
            $methodsToCalculate,
            $initialStateOfCalculations
        );
    }

    /**
     * @param $reputationEventsWild
     * @param string[] $methodsToCalculate
     * @param int[] $initialStateOfCalculations
     * @return \Mikron\ReputationList\Domain\Entity\Reputation[]
     */
    public function createFromReputationEvents($reputationEventsWild, $methodsToCalculate, $initialStateOfCalculations)
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
                $methodsToCalculate,
                $initialStateOfCalculations
            );
            $reputations[$reputation->getReputationNetwork()->getCode()] = $reputation;
        }

        return $reputations;
    }
}
