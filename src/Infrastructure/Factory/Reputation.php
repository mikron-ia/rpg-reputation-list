<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

/**
 * Class Reputation
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
class Reputation
{
    /**
     * @param \Mikron\ReputationList\Domain\Entity\ReputationEvent[] $reputationEvents
     * @return \Mikron\ReputationList\Domain\Entity\Reputation;
     */
    public function createFromReputationEvents($reputationEvents)
    {
        $reputations = [];

        foreach ($reputationEvents as $reputationEvent) {
            $reputationEventRepCode = $reputationEvent->getReputationNetwork()->getCode();

            if (!isset($reputations[$reputationEventRepCode])) {
                $reputations[$reputationEventRepCode] = [];
            }

            $reputations[$reputationEventRepCode][] = $reputationEvent;
        }

        return $reputations;
    }
}