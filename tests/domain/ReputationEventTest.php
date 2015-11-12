<?php

use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;
use Mikron\ReputationList\Infrastructure\Factory\StorageIdentification;

class ReputationEventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $identification
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function isDbIdCorrect($identification, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($identification, $reputationNetwork, $value, $event);
        $this->assertEquals($identification->getDbId(), $repEvent->getDbId());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $identification
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function isIdentificationCorrect($identification, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($identification, $reputationNetwork, $value, $event);
        $this->assertEquals($identification, $repEvent->getIdentification());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $identification
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function isNetworkCorrect($identification, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($identification, $reputationNetwork, $value, $event);
        $this->assertEquals($reputationNetwork, $repEvent->getReputationNetwork());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $identification
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function isValueCorrect($identification, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($identification, $reputationNetwork, $value, $event);
        $this->assertEquals($value, $repEvent->getValue());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $identification
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function simpleDataIsCorrect($identification, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($identification, $reputationNetwork, $value, $event);

        $expectation = [
            "name" => $repEvent->getName(),
            "value" => $repEvent->getValue()
        ];

        $this->assertEquals($expectation, $repEvent->getSimpleData());
    }

    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param $identification
     * @param $reputationNetwork
     * @param $value
     * @param $event
     */
    public function completeDataIsCorrect($identification, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($identification, $reputationNetwork, $value, $event);

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

        $idFactory = new StorageIdentification();
        $identification = $idFactory->createFromData(1, null);

        return [
            [$identification, $reputationNetwork, 5, null],
        ];
    }
}
