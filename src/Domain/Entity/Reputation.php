<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Displayable;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

/**
 * Class Reputation - a combination of all ReputationEvents and reputation object for Person
 * This class SHOULD NOT have a DB representation - it is calculated ad hoc
 *
 * @package Mikron\ReputationList\Domain\Entity
 */
class Reputation implements Displayable
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

    /**
     * @return ReputationNetwork
     */
    public function getReputationNetwork()
    {
        return $this->reputationNetwork;
    }

    /**
     * @return ReputationEvent[]
     */
    public function getReputationEvents()
    {
        return $this->reputationEvents;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->reputationNetwork->getName();
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->reputationNetwork->getCode();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->reputationNetwork->getDescription();
    }

    /**
     * @return array Simple representation of the object content, fit for basic display
     */
    public function getSimpleData()
    {
        return [
            "name" => $this->getName()
        ];
    }

    /**
     * @return array Complete representation of public parts of object content, fit for full card display
     */
    public function getCompleteData()
    {
        return [
            "name" => $this->getName(),
            "code" => $this->getCode(),
            "value" => $this->getValue()
        ];
    }
}
