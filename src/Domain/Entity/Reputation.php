<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

/**
 * Class Reputation - a combination of all ReputationEvents and reputation object for Person
 * This class SHOULD NOT have a DB representation - it is calculated ad hoc
 *
 * @package Mikron\ReputationList\Domain\Entity
 */
class Reputation
{
    /**
     * @var ReputationNetwork Network the Reputation belongs to
     */
    private $reputationNetwork;

    /**
     * @var ReputationEvent[] Events attached to the reputation
     * @todo Do something about redundancy - there is repNetwork as field and repNetwork in events
     */
    private $reputationEvents;

    /**
     * @var int Reputation value
     */
    private $value;

    /**
     * Reputation constructor.
     * @param ReputationNetwork $reputationNetwork
     * @param ReputationEvent[] $reputationEvents
     */
    public function __construct(ReputationNetwork $reputationNetwork, array $reputationEvents)
    {
        $this->reputationNetwork = $reputationNetwork;
        $this->reputationEvents = $reputationEvents;
        $this->value = $this->calculateValue();
    }

    /**
     * @return int Sum of all reputation event values
     */
    private function calculateValue()
    {
        $sum = 0;

        foreach($this->reputationEvents as $repEvent) {
            $sum += $repEvent->getValue();
        }

        return $sum;
    }

}