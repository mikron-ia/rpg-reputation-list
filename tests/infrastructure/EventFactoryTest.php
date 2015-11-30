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
        $this->eventFactory->unwrapEvent(null, "test");
    }

    /**
     * @test
     * @dataProvider provideCorrectArrayAndExpectedSearchResults
     * @param $array
     * @param $id
     * @param $expectedIdResult
     * @param $key
     * @param $expectedKeyResult
     */
    public function eventFromDbIsFoundById($array, $id, $expectedIdResult, $key, $expectedKeyResult)
    {
        $dbStub = $this->prepareDbStub($array, $id, $expectedIdResult, $key, $expectedKeyResult);

        $expectedEventWrapped = $this->eventFactory->createFromCompleteArray([$expectedIdResult]);
        $expectedEvent = array_pop($expectedEventWrapped);
        $actualEvent = $this->eventFactory->retrieveEventFromDbById($dbStub, $id);

        $this->assertEquals($expectedEvent, $actualEvent);
    }

    /**
     * @test
     * @dataProvider provideCorrectArrayAndExpectedSearchResults
     * @param $array
     * @param $id
     * @param $expectedIdResult
     * @param $key
     * @param $expectedKeyResult
     */
    public function eventFromDbIsFoundByKey($array, $id, $expectedIdResult, $key, $expectedKeyResult)
    {
        $dbStub = $this->prepareDbStub($array, $id, $expectedIdResult, $key, $expectedKeyResult);

        $expectedEventWrapped = $this->eventFactory->createFromCompleteArray([$expectedKeyResult]);
        $expectedEvent = array_pop($expectedEventWrapped);
        $actualEvent = $this->eventFactory->retrieveEventFromDbByKey($dbStub, $key);

        $this->assertEquals($expectedEvent, $actualEvent);
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
                        "key" => "0000000000000000000000000000000000000001",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 3,
                        "key" => "0000000000000000000000000000000000000002",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 4,
                        "key" => "0000000000000000000000000000000000000003",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 5,
                        "key" => "0000000000000000000000000000000000000004",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                ]
            ]
        ];
    }

    public function provideCorrectArrayAndExpectedSearchResults()
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
                        "key" => "0000000000000000000000000000000000000001",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 3,
                        "key" => "0000000000000000000000000000000000000002",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 4,
                        "key" => "0000000000000000000000000000000000000003",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 5,
                        "key" => "0000000000000000000000000000000000000004",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                ],
                1,
                [
                    "event_id" => 1,
                    "key" => "0000000000000000000000000000000000000000",
                    "name" => "Test Event",
                    "description" => "Test description"
                ],
                "0000000000000000000000000000000000000002",
                [
                    "event_id" => 3,
                    "key" => "0000000000000000000000000000000000000002",
                    "name" => "Test Event",
                    "description" => "Test Description"
                ],
            ]
        ];
    }

    private function prepareDbStub($array, $id, $expectedIdResult, $key, $expectedKeyResult)
    {
        $dbStub = $this->getMockBuilder('Mikron\\ReputationList\\Domain\\Blueprint\\StorageEngine')->getMock();
        $dbStub->method('selectAll')->willReturn($array);
        $dbStub->method('selectByKey')->willReturn([$expectedKeyResult]);
        $dbStub->method('selectByPrimaryKey')->willReturn([$expectedIdResult]);

        return $dbStub;
    }
}
