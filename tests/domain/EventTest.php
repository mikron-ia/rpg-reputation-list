<?php

use Mikron\ReputationList\Domain\Entity\Event;
use Mikron\ReputationList\Infrastructure\Factory\StorageIdentification;

class EventTest extends PHPUnit_Framework_TestCase
{
    private $identification;

    protected function setUp()
    {
        $idFactory = new StorageIdentification();
        $this->identification = $idFactory->createFromData(1, null);
    }

    /**
     * @test
     */
    public function identificationIsCorrect()
    {
        $event = new Event($this->identification, "Test Event", "Test Description");
        $this->assertEquals($this->identification, $event->getIdentification());
    }

    /**
     * @test
     */
    public function idIsCorrect()
    {
        $event = new Event($this->identification, "Test Event", "Test Description");
        $this->assertEquals(1, $event->getDbId());
    }

    /**
     * @test
     */
    public function nameIsCorrect()
    {
        $event = new Event($this->identification, "Test Event", "Test Description");
        $this->assertEquals("Test Event", $event->getName());
    }

    /**
     * @test
     */
    public function descriptionIsCorrect()
    {
        $event = new Event($this->identification, "Test Event", "Test Description");
        $this->assertEquals("Test Description", $event->getDescription());
    }

    /**
     * @test
     */
    public function simpleDataIsCorrect()
    {
        $event = new Event($this->identification, "Test Event", "Test Description");
        $this->assertEquals(["name" => "Test Event"], $event->getSimpleData());
    }

    /**
     * @test
     */
    public function completeDataIsCorrect()
    {
        $event = new Event($this->identification, "Test Event", "Test Description");
        $this->assertEquals(["name" => "Test Event", "description" => "Test Description"], $event->getCompleteData());
    }
}
