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
     * @param $values
     * @param $currentState
     * @return \int[]
     */
    public static function calculateBasic($values, $currentState)
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
     * @param $values
     * @param $currentState
     * @return array
     */
    public static function calculateLowestAndHighest($values, $currentState)
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
     * @param $values
     * @param $currentState
     * @return int|number
     */
    public static function calculateAbsolute($values, $currentState)
    {
        $result = 0;

        foreach($values as $value) {
            $result += abs($value);
        }

        return [
            'absolute' => $result
        ];
    }
}
