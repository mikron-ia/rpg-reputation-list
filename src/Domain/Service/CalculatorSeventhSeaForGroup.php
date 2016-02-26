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

    /**
     * @param int[] $membersReputations
     * @return int
     */
    public function calculateInfluencesFromMemberReputations($membersReputations)
    {
        $influences = [];
        $sumOfWeights = 0;

        foreach ($membersReputations as $repCode => $memberReputations) {
            /** @var Reputation[] $memberReputations */

            var_dump($memberReputations); die;

            //$values = $memberReputations->getValues([]);

            $influence = isset($values['influence'])?$values['influence']:0;
            $weight = isset($values['weight'])?$values['weight']:1;

            $influences[] = $influence*$weight;
            $sumOfWeights += $weight;
        }

        $sumOfInfluences = 0;

        foreach($influences as $influence) {
            $sumOfInfluences += round($influence / $sumOfWeights, 0);
        }

        return $sumOfInfluences;
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

        if (isset($parameters['influence.memberReputations'])) {
            $influenceFromMembers = $this->calculateInfluencesFromMemberReputations($parameters['influence.memberReputations']);
        } else {
            $influenceFromMembers = [];
        }

        $valuesAdjusted = $this->adjustValuesWithInfluences($values, $influenceFromMembers);

        $balance = $this->calculateBalanceFromNewValues($valuesAdjusted);

        $absolutes = $this->calculateAbsolute($valuesAdjusted);

        $dice = $this->calculateDice($basics + $balance + $maximums);
        $recognitionValue = $this->calculateRecognitionValue($absolutes);
        $recognitionDice = $this->calculateRecognitionDice($recognitionValue);

        $this->results = array_merge($basics, $maximums, $balance, $absolutes, $dice, $recognitionValue, $recognitionDice);
    }
}
