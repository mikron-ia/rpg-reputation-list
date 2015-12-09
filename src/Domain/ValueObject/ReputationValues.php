<?php

namespace Mikron\ReputationList\Domain\ValueObject;

use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;
use Mikron\ReputationList\Domain\Exception\MissingComponentException;
use Mikron\ReputationList\Domain\Service\CalculatorGeneric;

/**
 * Class ReputationValues - value object responsible for processing and storing pure numbers associated with Reputation
 * @package Mikron\ReputationList\Domain\ValueObject
 */
class ReputationValues
{
    /**
     * @var int[] Reputation values
     */
    private $values;

    /**
     * @var int[] All values derived from $values
     */
    private $results;

    /**
     * ReputationValues constructor.
     * @param \int[] $values List of reputation values
     * @param array $methodsToUse Methods that are supposed to be used to calculate results other than basics
     * @todo Parameters from calculateComplex() must be moved to configuration, likely system
     */
    public function __construct(array $values, array $methodsToUse = [])
    {
        $this->values = $values;

        $this->results = CalculatorGeneric::calculateSimple($this->values, []);

        $methodsToUse = [
            'generic' => [
                'calculateLowestAndHighest',
                'calculateAbsolute'
            ],
            'seventhSea' => [
                'seventhSeaCalculateDice',
                'seventhSeaCalculateRecognitionValue',
                'seventhSeaCalculateRecognitionDice'
            ]
        ];

        $this->calculateComplex($methodsToUse);
    }

    /**
     * Calculates more advanced derived values
     * @param $complex
     * @throws MissingComponentException
     */
    public function calculateComplex($complex)
    {
        foreach ($complex as $packName => $packMethods) {
            $packClassName = '\Mikron\ReputationList\Domain\Service\Calculator' . ucfirst($packName);

            if (!class_exists($packClassName)) {
                throw new MissingComponentException(
                    "Unable to find required class",
                    "Unable to find required class $packClassName"
                );
            }

            $packObject = new $packClassName();
            foreach ($packMethods as $packMethod) {
                $currentState = $this->getAll();
                $result = $packObject::$packMethod($this->values, $currentState);
                $this->results = array_merge($this->results, $result);
            }
        }
    }

    /**
     * Legacy method for value return
     *
     * @return int
     */
    public function getValue()
    {
        return $this->results['balance'];
    }

    /**
     * Returns all basics and configured advanced
     * @return int[]
     */
    public function getAll()
    {
        return $this->results;
    }
}
