<?php

namespace Mikron\ReputationList\Domain\Service;

use Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException;

/**
 * Class CalculatorSeventhSea
 *
 * seventhSea.calculateDice - calculates reputation dice based on balance and local extremes
 * seventhSea.calculateRecognitionValue - calculates recognition value (fame of the person)
 * seventhSea.calculateRecognitionDice - calculates recognition dice (used in tests of recognition)
 *
 * @package Mikron\ReputationList\Domain\Service\Calculator
 */
final class CalculatorSeventhSea extends CalculatorGeneric
{
    /**
     * Calculates dice according to balance and extremes. Requires calculateLowestAndHighest() to be called first
     * @todo Calculation of group rep that includes influence reputation - in this or in separate method
     * @param int[] $currentState
     * @return int[]
     * @throws MissingCalculationBaseException
     */
    public function calculateDice($currentState)
    {
        if (!isset($currentState['negativeMin']) || !isset($currentState['positiveMax'])) {
            throw new MissingCalculationBaseException(
                "Missing values necessary to operate",
                "Missing extremes. Call calculateLowestAndHighest() first."
            );
        }

        if ($currentState['balance'] < 5 && $currentState['balance'] > -5) {
            $dice = 0;
        } elseif ($currentState['balance'] > 0) {
            if ($currentState['balance'] == $currentState['positiveMax']) {
                $dice = ceil($currentState['balance'] / 10);
            } else {
                $dice = floor($currentState['balance'] / 10);
            }
        } else {
            if ($currentState['balance'] == $currentState['negativeMin']) {
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
     * @todo: either force parameters with div / multi OR move influence calculation to group
     */
    public function calculateInfluenceExtended($currentState)
    {
        if (
            isset($currentState['balance']) &&
            isset($currentState['influenceDivider']) &&
            isset($currentState['influenceMultiplier'])
        ) {
            if ($currentState['influenceDivider'] == 0) {
                throw new MissingCalculationBaseException(
                    "Erroneous value among values necessary to operate",
                    "Influence divider is zero. Provide a valid one as parameter."
                );
            }

            return [
                'influence' => (int)round(
                    ($currentState['balance'] * $currentState['influenceMultiplier']) / $currentState['influenceDivider'],
                    0
                )
            ];
        } else {
            return [];
        }
    }

    /**
     * Performs the calculations
     *
     * @param array $values
     * @return \int[]
     */
    function perform(array $values)
    {
        $basics = parent::perform($values);
        $dice = $this->calculateDice($basics);
        $recognitionValue = $this->calculateRecognitionValue($basics);
        $recognitionDice = $this->calculateRecognitionDice($basics);
        $influenceExtended = $this->calculateInfluenceExtended($basics); //NOTE: basics WILL NOT SUFFICE

        return array_merge($basics, $dice, $recognitionValue, $recognitionDice, $influenceExtended);
    }
}
