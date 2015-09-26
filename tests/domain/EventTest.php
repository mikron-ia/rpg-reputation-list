<?php

use Mikron\ReputationList\Domain\Entity\Event;

class EventTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function nameIsDisplayedCorrectly()
    {
        $event = new Event(null, "Test Event", "Test Description");
        $this->assertEquals("Test Event", $event->getName());
    }

    /**
     * @test
     */
    public function descriptionIsDisplayedCorrectly()
    {
        $event = new Event(null, "Test Event", "Test Description");
        $this->assertEquals("Test Description", $event->getDescription());
    }
}
