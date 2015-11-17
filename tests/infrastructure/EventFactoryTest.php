<?php

use Mikron\ReputationList\Infrastructure\Factory\Event;

class EventFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Event
     */
    protected $eventFactory;

    protected function setUp()
    {
        $this->eventFactory = new Event();
    }

    /**
     * @test
     * @dataProvider provideCorrectRow
     * @param $dbId
     * @param $name
     * @param $description
     */
    public function arrayFactoryReturnsEvent($dbId, $name, $description)
    {
        $event = $this->eventFactory->createFromSingleArray($dbId, $name, $description);
        $this->assertInstanceOf("Mikron\\ReputationList\\Domain\\Entity\\Event", $event);
    }

    /**
     * @test
     * @dataProvider provideCorrectArray
     * @param $array
     */
    public function massiveFactoryReturnsEvents($array)
    {
        $events = $this->eventFactory->createFromCompleteArray($array);
        $this->assertContainsOnlyInstancesOf("Mikron\\ReputationList\\Domain\\Entity\\Event", $events);
    }

    public function provideCorrectRow()
    {
        return [
            ["event_id" => 1, "name" => "Test Event", "description" => "Test Description"],
        ];
    }

    public function provideCorrectArray()
    {
        return [
            [
                [
                    ["event_id" => 1, "name" => "Test Event", "description" => "Test Description"],
                    ["event_id" => 2, "name" => "Test Event", "description" => "Test Description"],
                    ["event_id" => 3, "name" => "Test Event", "description" => "Test Description"],
                    ["event_id" => 4, "name" => "Test Event", "description" => "Test Description"],
                    ["event_id" => 5, "name" => "Test Event", "description" => "Test Description"],
                ]
            ]
        ];
    }
}
