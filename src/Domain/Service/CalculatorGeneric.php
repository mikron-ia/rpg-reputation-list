<?php

namespace Mikron\ReputationList\Domain\Service;

/**
 * Class CalculatorGeneric - basic calculations that are likely to be common
 *
 * Contains:
 * generic.calculateBasic - gives sum of positive values, negative values and total balance
 * generic.calculateLowestAndHighest - gives local extremes (minimums and maximums)
 * generic.calculateAbsolute - gives sum of absolute values
 *
 * @package Mikron\ReputationList\Domain\Service
 */
class CalculatorGeneric
{
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
     * @param array $values
     * @return \int[]
     */
    public function perform(array $values)
    {
        $basics = $this->calculateBasic($values);
        $maximums = $this->calculateLowestAndHighest($values);
        $absolutes = $this->calculateAbsolute($values);

        return array_merge($basics, $maximums, $absolutes);
    }
}
