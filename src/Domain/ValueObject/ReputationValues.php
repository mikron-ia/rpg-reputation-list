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
     * @throws ExceptionWithSafeMessage
     */
    public function calculateComplex($complex)
    {
        foreach ($complex as $packName => $packMethods) {
            $packClassName = '\Mikron\ReputationList\Domain\Service\Calculator' . ucfirst($packName);

            if (!class_exists($packClassName)) {
                throw new ExceptionWithSafeMessage(
                    "Unable to find required class",
                    "Unable to find required class $packClassName"
                );
            }

            $packObject = new $packClassName();
            foreach ($packMethods as $packMethod) {
                $currentState = $this->getAll();
                $result = call_user_func([$packObject, $packMethod], $this->values, $currentState);
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
}
