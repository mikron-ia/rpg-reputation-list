<?php

use Mikron\ReputationList\Domain\Service\CalculatorGeneric;

class ReputationValuesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function calculateComplexFailsIfClassDoesNotExist()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingComponentException');
        $reputationValues = new \Mikron\ReputationList\Domain\ValueObject\ReputationValues([], new CalculatorGeneric(), []);

        $reputationValues->calculate(['ClassThatDoesNotExist.methodInClassThatDoesNotExist']);
    }

    /**
     * @test
     */
    public function calculateComplexFailsIfMethodNameLacksClass()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\ErroneousComponentException');
        $reputationValues = new \Mikron\ReputationList\Domain\ValueObject\ReputationValues([], new CalculatorGeneric(), []);

        $reputationValues->calculate(['methodWithoutAClass']);
    }
}
