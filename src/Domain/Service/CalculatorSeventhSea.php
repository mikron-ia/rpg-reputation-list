<?php

namespace Mikron\ReputationList\Domain\Service;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
use Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException;

/**
 * Class CalculatorSeventhSea
 *
 * @package Mikron\ReputationList\Domain\Service\Calculator
 */
class CalculatorSeventhSea extends CalculatorGeneric implements Calculator
{
    /**
     * Calculates dice according to balance and extremes. Requires calculateLowestAndHighest() to be called first
     * @param int[] $currentState
     * @return int[]
     * @throws MissingCalculationBaseException
     */
    public function calculateDice($currentState)
    {
        if (!isset($currentState['balance'])) {
            throw new MissingCalculationBaseException(
                "Missing values necessary to operate",
                "Missing balance. Call calculateBasic() first."
            );
        }

        if (!isset($currentState['negativeMin']) || !isset($currentState['positiveMax'])) {
            throw new MissingCalculationBaseException(
                "Missing values necessary to operate",
                "Missing extremes. Call calculateLowestAndHighest() first."
            );
        }

        if ($currentState['balance'] < 5 && $currentState['balance'] > -5) {
            $dice = 0;
        } elseif ($currentState['balance'] > 0) {
            if ($currentState['balance'] >= $currentState['positiveMax']) {
                $dice = ceil($currentState['balance'] / 10);
            } else {
                $dice = floor($currentState['balance'] / 10);
            }
        } else {
            if ($currentState['balance'] <= $currentState['negativeMin']) {
                $dice = floor($currentState['balance'] / 10);
            } else {
                $dice = ceil($currentState['balance'] / 10);
            }
        }
        return [
            'dice' => (int)$dice
        ];
    }

    /**
     * @param int[] $currentState
     * @return int[]
     * @throws MissingCalculationBaseException
     */
    public function calculateRecognitionValue($currentState)
    {
        if (!isset($currentState['absolute'])) {
            throw new MissingCalculationBaseException(
                "Missing values necessary to operate",
                "Missing absolute. Call calculateAbsolute() first."
            );
        }

        return [
            'recognition' => $currentState['absolute']
        ];
    }

    /**
     * @param int[] $currentState
     * @return int[]
     * @throws MissingCalculationBaseException
     */
    public function calculateRecognitionDice($currentState)
    {
        if (!isset($currentState['recognition'])) {
            throw new MissingCalculationBaseException(
                "Missing values necessary to operate",
                "Missing absolute. Call seventhSeaCalculateRecognitionValue() first."
            );
        }

        return [
            'recognitionDice' => round($currentState['recognition'] / 10)
        ];
    }

    /**
     * @param $currentState
     * @return array
     * @throws MissingCalculationBaseException
     */
    public function calculateInfluenceExtended($currentState)
    {
        if (!isset($currentState['balance'])) {
            throw new MissingCalculationBaseException(
                "Missing values necessary to operate",
                "Missing balance. Call calculateBasic() first."
            );
        }

        if (!isset($currentState['influence.weight'])) {
            $weight = 1;
        } else {
            $weight = $currentState['influence.weight'];
        }

        return [
            'influence' => (int)round($currentState['balance'] * $weight, 0),
            'weight' => $weight
        ];
    }

    /**
     * Performs the calculations
     *
     * @param int[] $values
     * @param array $parameters
     * @return \int[]
     * @throws MissingCalculationBaseException
     */
    function perform($values, $parameters)
    {
        $basics = $this->calculateBasic($values);
        $maximums = $this->calculateLowestAndHighest($values);
        $absolutes = $this->calculateAbsolute($values);

        $dice = $this->calculateDice($basics + $maximums);
        $recognitionValue = $this->calculateRecognitionValue($absolutes);
        $recognitionDice = $this->calculateRecognitionDice($recognitionValue);

        $influenceExtended = $this->calculateInfluenceExtended($basics + $parameters);

        $this->results = array_merge($basics, $maximums, $absolutes, $dice, $recognitionValue, $recognitionDice, $influenceExtended);
    }
}
