<?php

namespace Mikron\ReputationList\Domain\Service;

use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;

/**
 * Class CalculatorSeventhSea
 *
 * seventhSea.calculateDice - calculates reputation dice based on balance and local extremes
 * seventhSea.calculateRecognitionValue - calculates recognition value (fame of the person)
 * seventhSea.calculateRecognitionDice - calculates recognition dice (used in tests of recognition)
 *
 * @package Mikron\ReputationList\Domain\Service\Calculator
 */
class CalculatorSeventhSea
{
    /**
     * Calculates dice according to balance and extremes. Requires calculateLowestAndHighest() to be called first
     * @todo Calculation of group rep that includes influence reputation - in this or in separate method
     * @param $values
     * @param $currentState
     * @return int[]
     * @throws ExceptionWithSafeMessage
     */
    public static function calculateDice($values, $currentState)
    {
        if (!isset($currentState['negativeMin']) || !isset($currentState['positiveMax'])) {
            throw new ExceptionWithSafeMessage(
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
     * @param int[] $values
     * @param int[] $currentState
     * @return int[]
     * @throws ExceptionWithSafeMessage
     */
    public static function calculateRecognitionValue($values, $currentState)
    {
        if (!isset($currentState['absolute'])) {
            throw new ExceptionWithSafeMessage(
                "Missing values necessary to operate",
                "Missing absolute. Call calculateAbsolute() first."
            );
        }

        return [
            'recognition' => $currentState['absolute']
        ];
    }

    /**
     * @param int[] $values
     * @param int[] $currentState
     * @return int[]
     * @throws ExceptionWithSafeMessage
     */
    public static function calculateRecognitionDice($values, $currentState)
    {
        if (!isset($currentState['recognition'])) {
            throw new ExceptionWithSafeMessage(
                "Missing values necessary to operate",
                "Missing absolute. Call seventhSeaCalculateRecognitionValue() first."
            );
        }

        return [
            'recognitionDice' => round($currentState['recognition']/10)
        ];
    }
}
