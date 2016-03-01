<?php

use Mikron\ReputationList\Domain\Service\CalculatorGeneric;

class ReputationValuesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function calculateComplexFailsIfNoCalculatorProvided()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingComponentException');
        new \Mikron\ReputationList\Domain\ValueObject\ReputationValues([], [], null, []);
    }
}
