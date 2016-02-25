<?php

namespace Mikron\ReputationList\Domain\Service;

use Mikron\ReputationList\Domain\Blueprint\Calculator;
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

    /**
     * @param int[] $influences
     * @return int
     */
    public function calculateSumOfInfluences($influences)
    {
        $sumOfInfluences = 0;

        foreach($influences as $influence) {
            $sumOfInfluences += $influence;
        }

        return $influences;
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

        $values[] = $influences;

        return $values;
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

        if (isset($parameters['influence.memberInfluences'])) {
            $influenceFromMembers = $this->calculateSumOfInfluences($parameters['influence.memberInfluences']);
        } else {
            $influenceFromMembers = [];
        }

        $valuesAdjusted = $this->adjustValuesWithInfluences($values, $influenceFromMembers);

        $absolutes = $this->calculateAbsolute($valuesAdjusted);

        $dice = $this->calculateDice($basics + $maximums);
        $recognitionValue = $this->calculateRecognitionValue($absolutes);
        $recognitionDice = $this->calculateRecognitionDice($recognitionValue);

        $this->results = array_merge($basics, $maximums, $absolutes, $dice, $recognitionValue, $recognitionDice);
    }
}
