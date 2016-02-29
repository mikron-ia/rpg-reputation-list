<?php

use Mikron\ReputationList\Domain\ValueObject\ReputationInfluence;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

class ReputationInfluenceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider ReputationInfluenceWithNoEventData
     * @param $reputationNetwork
     * @param $value
     * @param $divisor
     */
    public function isNetworkCorrect($reputationNetwork, $value, $divisor)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value, $divisor);
        $this->assertEquals($reputationNetwork, $repInfluence->getReputationNetwork());
    }

    /**
     * @test
     * @dataProvider ReputationInfluenceWithNoEventData
     * @param $reputationNetwork
     * @param $value
     * @param $divisor
     */
    public function isValueCorrect($reputationNetwork, $value, $divisor)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value, $divisor);
        $this->assertEquals($value, $repInfluence->getValue());
    }

    /**
     * @test
     * @dataProvider ReputationInfluenceWithNoEventData
     * @param $reputationNetwork
     * @param $value
     * @param $divisor
     */
    public function simpleDataIsCorrect($reputationNetwork, $value, $divisor)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value, $divisor);

        $expectation = [
            "name" => $repInfluence->getName(),
            "value" => $repInfluence->getValue()
        ];

        $this->assertEquals($expectation, $repInfluence->getSimpleData());
    }

    /**
     * @test
     * @dataProvider ReputationInfluenceWithNoEventData
     * @param $reputationNetwork
     * @param $value
     * @param $divisor
     */
    public function completeDataIsCorrect($reputationNetwork, $value, $divisor)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value, $divisor);

        $expectation = [
            "name" => $repInfluence->getName(),
            "code" => $repInfluence->getCode(),
            "value" => $repInfluence->getValue()
        ];

        $this->assertEquals($expectation, $repInfluence->getCompleteData());
    }

    /**
     * @test
     * @dataProvider valueCases
     * @param $reputationNetwork
     * @param int $value
     * @param int $divisor
     * @param int $expectation
     */
    public function isValueCalculatedCorrectly($reputationNetwork, $value, $divisor, $expectation)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value, $divisor);
        $this->assertEquals($expectation, $repInfluence->getValue());
    }

    public function ReputationInfluenceWithNoEventData()
    {
        $reputationNetwork = new ReputationNetwork("c", ["name" => "CivicNet", "description" => "Corporations"]);
        return [
            [$reputationNetwork, 5, 1],
        ];
    }

    public function valueCases()
    {
        $reputationNetwork = new ReputationNetwork("c", ["name" => "CivicNet", "description" => "Corporations"]);
        return [
            [$reputationNetwork, 5, 1, 5],
            [$reputationNetwork, 0, 1, 0],
            [$reputationNetwork, 1, 1, 1],
            [$reputationNetwork, 10, 2, 5],
            [$reputationNetwork, 10, 3, 3],
            [$reputationNetwork, 11, 3, 4],
            [$reputationNetwork, 25, 10, 3],
            [$reputationNetwork, 25, 11, 2],
            [$reputationNetwork, 100, 11, 9],
            [$reputationNetwork, 5, 10, 1],
            [$reputationNetwork, 5, 11, 0],
            [$reputationNetwork, 6, 11, 1],
            [$reputationNetwork, 17, 11, 2],
        ];
    }
}
