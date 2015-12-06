<?php

namespace Mikron\ReputationList\Domain\ValueObject;


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
        $this->calculateComplex(['calculateLowestAndHighest', 'seventhSeaCalculateDice']);
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
        foreach ($complex as $method) {
            call_user_func([$this, $method]);
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
     */
    private function calculateLowestAndHighest()
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

        $this->complex['negativeMin'] = $lowest;
        $this->complex['positiveMax'] = $highest;
    }

    /**
     * Calculates dice according to balance and extremes. Requires calculateLowestAndHighest() to work
     * @todo Calculation of group rep that includes influence reputation - in this or in separate method
     */
    private function seventhSeaCalculateDice()
    {
        /* Extremes are used here */
        if (!isset($this->complex['negativeMin']) || !isset($this->complex['positiveMax'])) {
            $this->calculateLowestAndHighest();
        }

        if ($this->balance < 5 && $this->balance > -5) {
            $this->complex['dice'] = 0;
        } elseif ($this->balance > 0) {
            if ($this->balance == $this->complex['positiveMax']) {
                $this->complex['dice'] = ceil($this->balance / 10);
            } else {
                $this->complex['dice'] = floor($this->balance / 10);
            }
        } else {
            if ($this->balance == $this->complex['negativeMin']) {
                $this->complex['dice'] = floor($this->balance / 10);
            } else {
                $this->complex['dice'] = ceil($this->balance / 10);
            }
        }
    }
}
