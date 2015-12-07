<?php

namespace Mikron\ReputationList\Domain\Service;

class CalculatorGeneric
{
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
    public function calculateLowestAndHighest($values, $currentState)
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
}
