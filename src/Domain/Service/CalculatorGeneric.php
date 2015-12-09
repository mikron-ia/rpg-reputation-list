<?php

namespace Mikron\ReputationList\Domain\Service;

/**
 * Class CalculatorGeneric - basic calculations that are likely to be common
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
    public static function calculateSimple($values, $currentState)
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
