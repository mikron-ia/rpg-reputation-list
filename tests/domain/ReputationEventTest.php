<?php

use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

class ReputationEventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $dbId
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function isDbIdCorrect($dbId, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($dbId, $reputationNetwork, $value, $event);
        $this->assertEquals($dbId, $repEvent->getDbId());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $dbId
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function isNetworkCorrect($dbId, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($dbId, $reputationNetwork, $value, $event);
        $this->assertEquals($reputationNetwork, $repEvent->getReputationNetwork());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $dbId
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function isValueCorrect($dbId, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($dbId, $reputationNetwork, $value, $event);
        $this->assertEquals($value, $repEvent->getValue());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $dbId
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function simpleDataIsCorrect($dbId, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($dbId, $reputationNetwork, $value, $event);

        $expectation = [
            "name" => $repEvent->getName(),
            "value" => $repEvent->getValue()
        ];

        $this->assertEquals($expectation, $repEvent->getSimpleData());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $dbId
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function completeDataIsCorrect($dbId, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($dbId, $reputationNetwork, $value, $event);

        $expectation = [
            "name" => $repEvent->getName(),
            "code" => $repEvent->getCode(),
            "value" => $repEvent->getValue(),
            "event" => null
        ];

        $this->assertEquals($expectation, $repEvent->getCompleteData());
    }

    public function reputationEventWithNoEventData()
    {
        $reputationNetwork = new ReputationNetwork("c", ["name" => "CivicNet", "description" => "Corporations"]);

        return [
            [1, $reputationNetwork, 5, null],
        ];
    }

}
