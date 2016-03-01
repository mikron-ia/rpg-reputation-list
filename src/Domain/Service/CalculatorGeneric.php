<?php

namespace Mikron\ReputationList\Domain\Service;

use Mikron\ReputationList\Domain\Blueprint\Calculator;

/**
 * Class CalculatorGeneric - basic calculations that are likely to be common
 *
 * @package Mikron\ReputationList\Domain\Service
 */
class CalculatorGeneric implements Calculator
{
    protected $results = [];

    /**
     * Calculates basic sums
     * @param int[] $values
     * @return int[]
     */
    public function calculateBasic($values)
    {
        $negative = 0;
        $positive = 0;

        foreach ($values as $value) {
            if ($value > 0) {
                $positive += $value;
            } else {
                $negative += $value;
            }
        }

        return [
            'balance' => $positive + $negative,
            'negative' => $negative,
            'positive' => $positive,
        ];
    }

    /**
     * Calculates greatest extremes across history, ie. lowest ever and highest ever reputation
     *
     * Note: order in which changes occurred matter:
     *
     * 2, 1, -2 will generate  0 / 1 / 3
     * -2, 1, 2 will generate -2 / 1 / 1
     *
     * @param int[] $values
     * @return int[]
     */
    public function calculateLowestAndHighest($values)
    {
        $lowest = 0;
        $highest = 0;

        $cumulative = 0;

        foreach ($values as $value) {
            $cumulative += $value;

            if ($cumulative > $highest) {
                $highest = $cumulative;
            }

            if ($cumulative < $lowest) {
                $lowest = $cumulative;
            }
        }

        return [
            'negativeMin' => $lowest,
            'positiveMax' => $highest
        ];
    }

    /**
     * @param int[] $values
     * @return int[]
     */
    public function calculateAbsolute($values)
    {
        $result = 0;

        foreach ($values as $value) {
            $result += abs($value);
        }

        return [
            'absolute' => $result
        ];
    }

    /**
     * Performs the calculations
     *
     * @param int[] $values
     * @param int[] $influences
     * @param array $parameters
     * @return \int[]
     */
    public function perform($values, $influences, $parameters)
    {
        $basics = $this->calculateBasic($values);
        $maximums = $this->calculateLowestAndHighest($values);
        $absolutes = $this->calculateAbsolute($values);

        $this->results = array_merge($basics, $maximums, $absolutes);
    }

    /**
     * @return int[]
     */
    public function getResults()
    {
        return $this->results;
    }
}
