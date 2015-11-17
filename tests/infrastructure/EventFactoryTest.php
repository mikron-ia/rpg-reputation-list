<?php

use Mikron\ReputationList\Infrastructure\Factory\Event;

class EventFactoryTest extends  PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider correctDataProvider
     * @param $dbId
     * @param $name
     * @param $description
     */
    public function arrayFactoryReturnsEvent($dbId, $name, $description)
    {
        $eventFactory = new Event();

        $event = $eventFactory->createFromSingleArray($dbId, $name, $description);

        $this->assertInstanceOf("Mikron\\ReputationList\\Domain\\Entity\\Event", $event);
    }

    public function correctDataProvider()
    {
        return [
            [1, "Test Event", "Test Description"],
        ];
    }
}
