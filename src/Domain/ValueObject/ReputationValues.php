<?php

namespace Mikron\ReputationList\Domain\ValueObject;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
use Mikron\ReputationList\Domain\Exception\ErroneousComponentException;
use Mikron\ReputationList\Domain\Exception\MissingComponentException;

/**
 * Class ReputationValues - value object responsible for processing and storing pure numbers associated with Reputation
 * @package Mikron\ReputationList\Domain\ValueObject
 */
final class ReputationValues
{
    /**
     * @var int[] Reputation values
     */
    private $values;

    /**
     * @var int[] All values derived from $values
     */
    private $results = [];

    /**
     * ReputationValues constructor.
     * @param \int[] $values List of reputation values
     * @param \int[] $influences List of reputation influences
     * @param Calculator $calculator
     * @param \int[] $initialState
     * @throws MissingComponentException
     */
    public function __construct(array $values, $influences, $calculator, array $initialState = [])
    {
        $this->values = $values;
        $this->results = $initialState;

        if (empty($calculator)) {
            throw new MissingComponentException('No calculator provided');
        }

        $calculator->perform($values, $influences, $initialState);
        $this->results = $calculator->getResults();
    }

    /**
     * Returns all basics and configured advanced
     * @return int[]
     */
    public function getAll()
    {
        return $this->results;
    }
}
