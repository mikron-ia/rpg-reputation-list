<?php

use Mikron\ReputationList\Domain\Entity\Reputation;
use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

class ReputationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider correctReputationNetworkProvider
     * @param $repNet
     * @param $repEvents
     */
    public function reputationNetworkIsCorrect($repNet, $repEvents)
    {
        $reputation = new Reputation($repNet, $repEvents, []);
        $this->assertEquals($repNet, $reputation->getReputationNetwork());
    }

    /**
     * @test
     * @dataProvider correctReputationNetworkProvider
     * @param $repNet
     * @param $repEvents
     */
    public function simpleDataIsCorrect($repNet, $repEvents)
    {
        $reputation = new Reputation($repNet, $repEvents, []);

        $expectation = [
            "name" => $reputation->getName()
        ];

        $this->assertEquals($expectation, $reputation->getSimpleData());
    }

    /**
     * @test* @dataProvider correctReputationNetworkProvider
     * @param $repNet
     * @param $repEvents
     */
    public function completeDataIsCorrect($repNet, $repEvents)
    {
        $reputation = new Reputation($repNet, $repEvents, []);

        $expectation = [
            "name" => $reputation->getName(),
            "code" => $reputation->getCode(),
            "description" => $reputation->getDescription(),
            "value" => $reputation->getValues([])
        ];

        $this->assertEquals($expectation, $reputation->getCompleteData());
    }

    /**
     * @test
     * @dataProvider correctReputationNetworkProvider
     * @param $repNet
     * @param $repEvents
     */
    public function reputationEventsAreOfCorrectClass($repNet, $repEvents)
    {
        $reputation = new Reputation($repNet, $repEvents, []);
        $className = "Mikron\\ReputationList\\Domain\\Entity\\ReputationEvent";

        $this->assertContainsOnlyInstancesOf($className, $reputation->getReputationEvents());
    }

    public function correctReputationNetworkProvider()
    {
        $reputationNetwork = new ReputationNetwork("c", ["name" => "CivicNet", "description" => "Corporations"]);

        return [
            [
                $reputationNetwork,
                []
            ],
            [
                $reputationNetwork,
                [
                    new ReputationEvent(1, $reputationNetwork, 5, null)
                ]
            ],
            [
                $reputationNetwork,
                [
                    new ReputationEvent(1, $reputationNetwork, 5, null),
                    new ReputationEvent(2, $reputationNetwork, 2, null)
                ]
            ],
        ];
    }
}
