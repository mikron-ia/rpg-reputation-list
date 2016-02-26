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
     */
    public function isNetworkCorrect($reputationNetwork, $value)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value);
        $this->assertEquals($reputationNetwork, $repInfluence->getReputationNetwork());
    }

    /**
     * @test
     * @dataProvider ReputationInfluenceWithNoEventData
     * @param $reputationNetwork
     * @param $value
     */
    public function isValueCorrect($reputationNetwork, $value)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value);
        $this->assertEquals($value, $repInfluence->getValue());
    }

    /**
     * @test
     * @dataProvider ReputationInfluenceWithNoEventData
     * @param $reputationNetwork
     * @param $value
     */
    public function simpleDataIsCorrect($reputationNetwork, $value)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value);

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
     */
    public function completeDataIsCorrect($reputationNetwork, $value)
    {
        $repInfluence = new ReputationInfluence($reputationNetwork, $value);

        $expectation = [
            "name" => $repInfluence->getName(),
            "code" => $repInfluence->getCode(),
            "value" => $repInfluence->getValue()
        ];

        $this->assertEquals($expectation, $repInfluence->getCompleteData());
    }

    public function ReputationInfluenceWithNoEventData()
    {
        $reputationNetwork = new ReputationNetwork("c", ["name" => "CivicNet", "description" => "Corporations"]);
        return [
            [$reputationNetwork, 5],
        ];
    }
}
