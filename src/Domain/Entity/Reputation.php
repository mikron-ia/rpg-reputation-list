<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
use Mikron\ReputationList\Domain\Blueprint\Displayable;
use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;
use Mikron\ReputationList\Domain\ValueObject\ReputationInfluence;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;
use Mikron\ReputationList\Domain\ValueObject\ReputationValues;

/**
 * Class Reputation - a combination of all ReputationEvents and reputation object for Person
 * This class SHOULD NOT have a DB representation - it is calculated ad hoc
 *
 * @package Mikron\ReputationList\Domain\Entity
 */
final class Reputation implements Displayable
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
     * @var ReputationInfluence[] Influences on the reputation
     * Specifically, this describes additions that add to reputation value, but are not events
     */
    private $reputationInfluences;

    /**
     * @var ReputationValues Reputation value
     */
    private $value;

    /**
     * @var string[]
     */
    private $calculator;

    /**
     * @var string[]
     */
    private $initialParametersToCalculate;

    /**
     * Reputation constructor.
     * @param ReputationNetwork $reputationNetwork
     * @param ReputationEvent[] $reputationEvents
     * @param ReputationInfluence[] $reputationInfluences
     * @param Calculator $calculator
     * @param \string[] $initialParametersToCalculate
     */
    public function __construct(
        ReputationNetwork $reputationNetwork,
        array $reputationEvents,
        array $reputationInfluences,
        $calculator,
        array $initialParametersToCalculate
    )
    {
        $this->reputationNetwork = $reputationNetwork;
        $this->reputationEvents = $reputationEvents;
        $this->reputationInfluences = $reputationInfluences;
        $this->calculator = $calculator;
        $this->initialParametersToCalculate = $initialParametersToCalculate;

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

        $influences = [];

        foreach ($this->reputationInfluences as $reputationInfluence) {
            $influences[] = $reputationInfluence->getValue();
        }

        $value = new ReputationValues($values, $influences, $this->calculator, $this->initialParametersToCalculate);

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
     * @return int[]
     */
    public function getValues($config)
    {
        return $this->value->getAll();
    }

    /**
     * @return int
     * @throws ExceptionWithSafeMessage
     */
    public function getValue()
    {
        $values = $this->value->getAll();

        if (!isset($values['balance'])) {
            throw new ExceptionWithSafeMessage(
                'Reputation balance not available',
                'Reputation balance not available. Please add generic.calculateBasic to configuration'
            );
        }

        return $values['balance'];
    }

    /**
     * @return string[] Simple representation of the object content, fit for basic display
     */
    public function getSimpleData()
    {
        return [
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'value' => $this->getValue(),
        ];
    }

    /**
     * @return string[] Complete representation of public parts of object content, fit for full card display
     */
    public function getCompleteData()
    {
        return [
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'description' => $this->getDescription(),
            'value' => $this->getValues([]),
        ];
    }
}
