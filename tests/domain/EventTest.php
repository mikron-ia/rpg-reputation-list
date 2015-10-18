<?php

use Mikron\ReputationList\Domain\Entity\Event;

class EventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function idIsCorrect()
    {
        $event = new Event(1, "Test Event", "Test Description");
        $this->assertEquals(1, $event->getDBId());
    }

    /**
     * @test
     */
    public function nameIsCorrect()
    {
        $event = new Event(1, "Test Event", "Test Description");
        $this->assertEquals("Test Event", $event->getName());
    }

    /**
     * @test
     */
    public function descriptionIsCorrect()
    {
        $event = new Event(1, "Test Event", "Test Description");
        $this->assertEquals("Test Description", $event->getDescription());
    }

    /**
     * @test
     */
    public function simpleDataIsCorrect()
    {
        $event = new Event(1, "Test Event", "Test Description");
        $this->assertEquals(["name" => "Test Event"], $event->getSimpleData());
    }

    /**
     * @test
     */
    public function completeDataIsCorrect()
    {
        $event = new Event(1, "Test Event", "Test Description");
        $this->assertEquals(["name" => "Test Event", "description" => "Test Description"], $event->getCompleteData());
    }
}
