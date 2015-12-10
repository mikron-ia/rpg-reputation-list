<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Displayable;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;
use Mikron\ReputationList\Domain\ValueObject\ReputationValues;

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
     */
    private $reputationEvents;

    /**
     * @var ReputationValues Reputation value
     */
    private $value;

    /**
     * @var string[][]
     */
    private $methodsToCalculate;

    /**
     * Reputation constructor.
     * @param ReputationNetwork $reputationNetwork
     * @param ReputationEvent[] $reputationEvents
     * @param \string[] $methodsToCalculate
     */
    public function __construct(ReputationNetwork $reputationNetwork, array $reputationEvents, array $methodsToCalculate)
    {
        $this->reputationNetwork = $reputationNetwork;
        $this->reputationEvents = $reputationEvents;
        $this->methodsToCalculate = $methodsToCalculate;

        $this->value = $this->calculateValue();
    }


    /**
     * @return int Sum of all reputation event values
     */
    private function calculateValue()
    {
        $values = [];

        foreach ($this->reputationEvents as $repEvent) {
            $values[] = $repEvent->getValue();
        }

        $value = new ReputationValues($values, $this->methodsToCalculate);

        return $value;
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
     * @param $config
     * @return array
     */
    public function getValues($config)
    {
        return $this->value->getAll();
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
            "description" => $this->getDescription(),
            "value" => $this->getValues([]),
        ];
    }
}
