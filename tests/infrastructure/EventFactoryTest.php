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
     * @param $key
     * @param $name
     * @param $description
     */
    public function arrayFactoryReturnsEvent($dbId, $key, $name, $description)
    {
        $event = $this->eventFactory->createFromSingleArray($dbId, $key, $name, $description);
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

    /**
     * @test
     * @dataProvider provideWrappedCorrectRow
     * @param $eventWrapped
     * @throws \Mikron\ReputationList\Domain\Exception\EventNotFoundException
     */
    public function unwrappingWorks($eventWrapped)
    {
        $eventUnwrapped = $this->eventFactory->unwrapEvent($eventWrapped, "test");
        $this->assertInstanceOf("Mikron\\ReputationList\\Domain\\Entity\\Event",$eventUnwrapped);
    }

    /**
     * @test
     * @dataProvider provideWrappedCorrectRow
     * @throws \Mikron\ReputationList\Domain\Exception\EventNotFoundException
     */
    public function unwrappingFailsOnEmpty()
    {
        $this->setExpectedException("Mikron\\ReputationList\\Domain\\Exception\\EventNotFoundException");
        $eventUnwrapped = $this->eventFactory->unwrapEvent(null, "test");
    }

    public function provideCorrectRow()
    {
        return [
            [
                "event_id" => 1,
                "key" => "0000000000000000000000000000000000000000",
                "name" => "Test Event",
                "description" => "Test Description"
            ],
        ];
    }

    public function provideWrappedCorrectRow()
    {
        return [
            [$this->provideCorrectRow()]
        ];
    }

    public function provideCorrectArray()
    {
        return [
            [
                [
                    [
                        "event_id" => 1,
                        "key" => "0000000000000000000000000000000000000000",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 2,
                        "key" => "0000000000000000000000000000000000000000",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 3,
                        "key" => "0000000000000000000000000000000000000000",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 4,
                        "key" => "0000000000000000000000000000000000000000",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 5,
                        "key" => "0000000000000000000000000000000000000000",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                ]
            ]
        ];
    }
}
