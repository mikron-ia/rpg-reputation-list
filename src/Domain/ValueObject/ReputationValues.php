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
     * ReputationValues constructor.
     * @param \int[] $values
     * @param array $complex
     */
    public function __construct(array $values, array $complex = [])
    {
        $this->values = $values;

        $this->calculateSimple();
        $this->calculateComplex($complex);
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

        $advanced = [];

        return array_merge($basic, $advanced);
    }
}
