<?php

use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

class ReputationEventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $reputationNetwork
     * @param $repEvent
     */
    public function isDbIdCorrect($reputationNetwork, $repEvent)
    {
        $this->assertEquals(1, $repEvent->getDbId());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $reputationNetwork
     * @param $repEvent
     */
    public function isNetworkCorrect($reputationNetwork, $repEvent)
    {
        $this->assertEquals($reputationNetwork, $repEvent->getReputationNetwork());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $reputationNetwork
     * @param $repEvent
     */
    public function isValueCorrect($reputationNetwork, $repEvent)
    {
        $this->assertEquals(5, $repEvent->getValue());
    }

    public function reputationEventWithNoEventData()
    {
        $reputationNetwork = new ReputationNetwork("c", ["name" => "CivicNet", "description" => "Corporations"]);

        return [
            [
                $reputationNetwork,
                new ReputationEvent(1, $reputationNetwork, 5, null)
            ]
        ];
    }

}
