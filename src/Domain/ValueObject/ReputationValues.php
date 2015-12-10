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
    private $results = [];

    /**
     * ReputationValues constructor.
     * @param \int[] $values List of reputation values
     * @param array $methodsToUse Methods that are supposed to be used to calculate results other than basics
     */
    public function __construct(array $values, array $methodsToUse = [])
    {
        $this->values = $values;
        $this->calculate($methodsToUse);
    }

    /**
     * Calculates more advanced derived values
     * @param $methodsToUse
     * @throws MissingComponentException
     */
    public function calculate($methodsToUse)
    {
        foreach ($methodsToUse as $packMethod) {
            $explodedMethod = explode('.', $packMethod);

            if (!isset($explodedMethod[0]) || !isset($explodedMethod[1])) {
                throw new MissingComponentException(
                    "Incorrect method",
                    "Method $packMethod does not have class name or proper name"
                );
            }

            $packName = $explodedMethod[0];
            $packMethod = $explodedMethod[1];

            $packClassName = '\Mikron\ReputationList\Domain\Service\Calculator' . ucfirst($packName);

            if (!class_exists($packClassName)) {
                throw new MissingComponentException(
                    "Unable to find required class",
                    "Unable to find required class $packClassName"
                );
            }

            $packObject = new $packClassName();
            $currentState = $this->getAll();
            $result = $packObject::$packMethod($this->values, $currentState);
            $this->results = array_merge($this->results, $result);
        }
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
