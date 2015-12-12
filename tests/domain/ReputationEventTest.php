<?php

use Mikron\ReputationList\Domain\Entity\Event;
use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;
use Mikron\ReputationList\Domain\ValueObject\StorageIdentification;
use Mikron\ReputationList\Infrastructure\Factory\StorageIdentification as StorageIdentificationFactory;

class ReputationEventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider reputationEventWithNoEventData
     * @param StorageIdentification $identification
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
    public function isEventCorrect($identification, $reputationNetwork, $value, $event)
    {
        $repEvent = new ReputationEvent($identification, $reputationNetwork, $value, $event);
        $this->assertEquals($event, $repEvent->getEvent());
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
            "event" => $repEvent->getEvent()->getCompleteData()
        ];

        $this->assertEquals($expectation, $repEvent->getCompleteData());
    }

    public function reputationEventWithNoEventData()
    {
        $reputationNetwork = new ReputationNetwork("c", ["name" => "CivicNet", "description" => "Corporations"]);

        $idFactory = new StorageIdentificationFactory();
        $identification = $idFactory->createFromData(1, null);

        return [
            [$identification, $reputationNetwork, 5, new Event(null, "Event testowy", null)],
        ];
    }
}
