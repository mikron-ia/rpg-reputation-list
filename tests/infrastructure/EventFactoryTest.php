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
     * @param $time
     * @param $name
     * @param $description
     */
    public function arrayFactoryReturnsEvent($dbId, $key, $time, $name, $description)
    {
        $event = $this->eventFactory->createFromSingleArray($dbId, $key, $time, $name, $description);
        $this->assertInstanceOf('Mikron\ReputationList\Domain\Entity\Event', $event);
    }

    /**
     * @test
     * @dataProvider provideCorrectArray
     * @param $array
     */
    public function massiveFactoryReturnsEvents($array)
    {
        $events = $this->eventFactory->createFromCompleteArray($array);
        $this->assertContainsOnlyInstancesOf('Mikron\ReputationList\Domain\Entity\Event', $events);
    }

    /**
     * @test
     * @dataProvider provideCorrectRow
     * @param $eventWrapped
     * @throws \Mikron\ReputationList\Domain\Exception\EventNotFoundException
     */
    public function unwrappingWorks($eventWrapped)
    {
        $eventUnwrapped = $this->eventFactory->unwrapEvent([$eventWrapped], 'test');
        $this->assertInstanceOf('Mikron\ReputationList\Domain\Entity\Event', $eventUnwrapped);
    }

    /**
     * @test
     * @throws \Mikron\ReputationList\Domain\Exception\EventNotFoundException
     */
    public function unwrappingFailsOnEmpty()
    {
        $this->setExpectedException('Mikron\ReputationList\Domain\Exception\EventNotFoundException');
        $this->eventFactory->unwrapEvent(null, null);
    }

    /**
     * @test
     * @dataProvider provideCorrectArrayAndExpectedSearchResults
     * @param $array
     * @param $expectedIdResult
     * @param $expectedKeyResult
     */
    public function eventFromDbIsFoundById($array, $expectedIdResult, $expectedKeyResult)
    {
        $dbStub = $this->prepareDbStub($array, $expectedIdResult, $expectedKeyResult);

        $expectedEventWrapped = $this->eventFactory->createFromCompleteArray([$expectedIdResult]);
        $expectedEvent = array_pop($expectedEventWrapped);

        /* Note: due to mocking the DB, key does not matter */
        $actualEvent = $this->eventFactory->retrieveEventFromDbById(
            $dbStub,
            0
        );

        $this->assertEquals($expectedEvent, $actualEvent);
    }

    /**
     * @test
     * @dataProvider provideCorrectArrayAndExpectedSearchResults
     * @param $array
     * @param $expectedIdResult
     * @param $expectedKeyResult
     */
    public function eventFromDbIsFoundByKey($array, $expectedIdResult, $expectedKeyResult)
    {
        $dbStub = $this->prepareDbStub($array, $expectedIdResult, $expectedKeyResult);

        $expectedEventWrapped = $this->eventFactory->createFromCompleteArray([$expectedKeyResult]);
        $expectedEvent = array_pop($expectedEventWrapped);

        /* Note: due to mocking the DB, key does not matter */
        $actualEvent = $this->eventFactory->retrieveEventFromDbByKey(
            $dbStub,
            "0000000000000000000000000000000000000000"
        );

        $this->assertEquals($expectedEvent, $actualEvent);
    }

    public function provideCorrectRow()
    {
        return [
            [
                "event_id" => 1,
                "key" => "0000000000000000000000000000000000000000",
                "time" => "Test Time",
                "name" => "Test Event",
                "description" => "Test Description"
            ],
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
                        "time" => "Test Time",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 2,
                        "key" => "0000000000000000000000000000000000000001",
                        "time" => "Test Time",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 3,
                        "key" => "0000000000000000000000000000000000000002",
                        "time" => "Test Time",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 4,
                        "key" => "0000000000000000000000000000000000000003",
                        "time" => "Test Time",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                    [
                        "event_id" => 5,
                        "key" => "0000000000000000000000000000000000000004",
                        "time" => "Test Time",
                        "name" => "Test Event",
                        "description" => "Test Description"
                    ],
                ]
            ]
        ];
    }

    public function provideCorrectArrayAndExpectedSearchResults()
    {
        $arrayToExtract = $this->provideCorrectArray();
        $arrayExtracted = array_pop($arrayToExtract);
        $correctArray = array_pop($arrayExtracted);
        return [
            [
                $correctArray,
                $correctArray[1],
                $correctArray[2]
            ],
            [
                $correctArray,
                $correctArray[3],
                $correctArray[4]
            ],
        ];
    }

    private function prepareDbStub($array, $expectedIdResult, $expectedKeyResult)
    {
        $dbStub = $this->getMockBuilder('Mikron\ReputationList\Domain\Blueprint\StorageEngine')->getMock();
        $dbStub->method('selectAll')->willReturn($array);
        $dbStub->method('selectByKey')->willReturn([$expectedKeyResult]);
        $dbStub->method('selectByPrimaryKey')->willReturn([$expectedIdResult]);

        return $dbStub;
    }
}
