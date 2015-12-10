<?php

class ReputationValuesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function calculateComplexFailsIfClassDoesNotExist()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingComponentException');
        $reputationValues = new \Mikron\ReputationList\Domain\ValueObject\ReputationValues([], []);

        $reputationValues->calculateComplex(['NotExistentTestClass'=>[]]);
    }
}
