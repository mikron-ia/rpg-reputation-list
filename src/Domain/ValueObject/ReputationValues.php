<?php

namespace Mikron\ReputationList\Domain\ValueObject;


use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;

class ReputationValues
{
    /**
     * @var int[]
     */
    private $values;

    /**
     * @var int Sum of all negative values
     */
    private $negative;

    /**
     * @var int Sum of all positive values
     */
    private $positive;

    /**
     * @var int Sum of all values
     */
    private $balance;

    /**
     * @var int[] Complex calculations, sometimes system specific
     */
    private $complex;

    /**
     * ReputationValues constructor.
     * @param \int[] $values
     * @param array $complex
     * @todo Parameters from calculateComplex() must be moved to configuration, likely system
     */
    public function __construct(array $values, array $complex = [])
    {
        $this->values = $values;

        $this->calculateSimple();

        $currentComplex = [
            'generic' => ['calculateLowestAndHighest'],
            'seventhSea' => ['seventhSeaCalculateDice']
        ];

        $this->complex = [];

        $this->calculateComplex($currentComplex);
    }

    /**
     * Calculates basic sums
     */
    public function calculateSimple()
    {
        $this->negative = 0;
        $this->positive = 0;

        foreach ($this->values as $value) {
            if ($value > 0) {
                $this->positive += $value;
            } else {
                $this->negative += $value;
            }
        }

        $this->balance = $this->positive + $this->negative;
    }

    /**
     * Calculates more advanced derived values
     * @param $complex
     */
    public function calculateComplex($complex)
    {
        foreach ($complex as $packMethods) {
            foreach ($packMethods as $packMethod) {
                $currentState = $this->getAll();
                $result = call_user_func([$this, $packMethod], $currentState);
                $this->complex = array_merge($this->complex, $result);
            }
        }
    }

    /**
     * @return int
     */
    public function getNegative()
    {
        return $this->negative;
    }

    /**
     * @return int
     */
    public function getPositive()
    {
        return $this->positive;
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->getBalance();
    }

    /**
     * Returns all basics and configured advanced
     * @return int[]
     */
    public function getAll()
    {
        $basic = [
            'balance' => $this->getBalance(),
            'negative' => $this->getNegative(),
            'positive' => $this->getPositive(),
        ];

        return array_merge($basic, $this->complex);
    }

    /* System-specific methods */

    /**
     * Calculates greatest extremes across history, ie. lowest ever and highest ever reputation
     *
     * Note: order in which changes occurred matter:
     *
     * 2, 1, -2 will generate  0 / 1 / 3
     * -2, 1, 2 will generate -2 / 1 / 1
     *
     * @param $currentState
     * @return array
     */
    private function calculateLowestAndHighest($currentState)
    {
        $lowest = 0;
        $highest = 0;

        $cumulative = 0;

        foreach ($this->values as $value) {
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
     * Calculates dice according to balance and extremes. Requires calculateLowestAndHighest() to be called first
     * @todo Calculation of group rep that includes influence reputation - in this or in separate method
     * @param $currentState
     * @return array
     * @throws ExceptionWithSafeMessage
     */
    private function seventhSeaCalculateDice($currentState)
    {
        if (!isset($currentState['negativeMin']) || !isset($currentState['positiveMax'])) {
            throw new ExceptionWithSafeMessage(
                "Missing values necessary to operate",
                "Missing extremes. Call calculateLowestAndHighest() first."
            );
        }

        if ($this->balance < 5 && $this->balance > -5) {
            $dice = 0;
        } elseif ($this->balance > 0) {
            if ($this->balance == $currentState['positiveMax']) {
                $dice = ceil($this->balance / 10);
            } else {
                $dice = floor($this->balance / 10);
            }
        } else {
            if ($this->balance == $currentState['negativeMin']) {
                $dice = floor($this->balance / 10);
            } else {
                $dice = ceil($this->balance / 10);
            }
        }
        return [
            'dice' => $dice
        ];
    }
}
