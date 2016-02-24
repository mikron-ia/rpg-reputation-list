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
     * @param int[] $values
     * @param array $parameters
     * @return \int[]
     * @throws MissingCalculationBaseException
     */
    function perform($values, $parameters)
    {
        parent::perform($values, $parameters);
        $basics = parent::getResults();

        $dice = $this->calculateDice($basics);
        $recognitionValue = $this->calculateRecognitionValue($basics);
        $recognitionDice = $this->calculateRecognitionDice($basics + $recognitionValue);
        $influenceExtended = $this->calculateInfluenceExtended($basics); //NOTE: basics WILL NOT SUFFICE

        return array_merge($basics, $dice, $recognitionValue, $recognitionDice, $influenceExtended);
    }
}
