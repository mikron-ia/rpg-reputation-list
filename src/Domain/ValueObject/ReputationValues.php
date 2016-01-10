<?php

namespace Mikron\ReputationList\Domain\ValueObject;

use Mikron\ReputationList\Domain\Exception\ErroneousComponentException;
use Mikron\ReputationList\Domain\Exception\MissingComponentException;

/**
 * Class ReputationValues - value object responsible for processing and storing pure numbers associated with Reputation
 * @package Mikron\ReputationList\Domain\ValueObject
 */
final class ReputationValues
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
     * @param \string[] $methodsToUse Methods that are supposed to be used to calculate results other than basics
     * @param \int[] $initialState
     * @throws ErroneousComponentException
     * @throws MissingComponentException
     */
    public function __construct(array $values, array $methodsToUse = [], array $initialState = [])
    {
        $this->values = $values;
        $this->results = $initialState;

        $this->calculate($methodsToUse);
    }

    /**
     * Calculates more advanced derived values
     * @param $methodsToUse
     * @throws ErroneousComponentException
     * @throws MissingComponentException
     */
    public function calculate($methodsToUse)
    {
        foreach ($methodsToUse as $packMethod) {
            $explodedMethod = explode('.', $packMethod);

            if (!isset($explodedMethod[0]) || !isset($explodedMethod[1])) {
                throw new ErroneousComponentException(
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
