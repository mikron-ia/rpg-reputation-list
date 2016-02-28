<?php

namespace Mikron\ReputationList\Domain\Service;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
use Mikron\ReputationList\Domain\Entity\Reputation;
use Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException;

/**
 * Class CalculatorSeventhSeaForGroup
 *
 * @package Mikron\ReputationList\Domain\Service\Calculator
 */
final class CalculatorSeventhSeaForGroup extends CalculatorSeventhSea implements Calculator
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
            'uninfluenced' => $positive + $negative,
            'negative' => $negative,
            'positive' => $positive,
        ];
    }

    public function calculateBalanceFromNewValues($values)
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
        ];
    }

    /**
     * Attaches influences to the end of the values array
     *
     * @param int[] $values
     * @param int[] $influences
     * @return int[]
     */
    public function adjustValuesWithInfluences($values, $influences)
    {
        return array_merge($values, $influences);
    }

    /**
     * Performs the calculations
     *
     * @param int[] $values
     * @param \int[] $influences
     * @param array $parameters
     * @return \int[]
     * @throws MissingCalculationBaseException
     */
    function perform($values, $influences, $parameters)
    {
        $basics = $this->calculateBasic($values);
        $maximums = $this->calculateLowestAndHighest($values);

        $valuesAdjusted = $this->adjustValuesWithInfluences($values, $influences);

        $balance = $this->calculateBalanceFromNewValues($valuesAdjusted);

        $absolutes = $this->calculateAbsolute($valuesAdjusted);

        $dice = $this->calculateDice($basics + $balance + $maximums);
        $recognitionValue = $this->calculateRecognitionValue($absolutes);
        $recognitionDice = $this->calculateRecognitionDice($recognitionValue);

        $this->results = array_merge(
            $basics,
            $maximums,
            $balance,
            $absolutes,
            $dice,
            $recognitionValue,
            $recognitionDice
        );
    }
}
