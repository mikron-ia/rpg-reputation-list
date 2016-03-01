<?php

use Mikron\ReputationList\Domain\Exception\MissingComponentException;

class ReputationValuesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function calculateComplexFailsIfNoCalculatorProvided()
    {
        $this->setExpectedException(MissingComponentException::class);
        new \Mikron\ReputationList\Domain\ValueObject\ReputationValues([], [], null, []);
    }
}
