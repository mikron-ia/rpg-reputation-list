<?php

namespace Mikron\ReputationList\Domain\Blueprint;

/**
 * Interface for Calculators
 * @package Mikron\ReputationList\Domain\Blueprint
 */
interface Calculator
{
    /**
     * @param \int[] $values Reputation values
     * @param \int[] $influences
     * @param array $parameters Additional parameters, used by this specific calculator
     * @return \int[] All data calculated by the calculator
     */
    public function perform($values, $influences, $parameters);

    /**
     * @return int[] All data calculated by the calculator
     */
    public function getResults();
}
