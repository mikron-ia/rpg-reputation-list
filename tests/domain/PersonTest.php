<?php

use Mikron\ReputationList\Domain\Entity\Person;

class PersonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function idIsCorrect()
    {
        $person = new Person(1, "Test Name", "Test Description", []);
        $this->assertEquals(1, $person->getDBId());
    }

    /**
     * @test
     */
    public function nameIsCorrect()
    {
        $person = new Person(1, "Test Name", "Test Description", []);
        $this->assertEquals("Test Name", $person->getName());
    }

    /**
     * @test
     */
    public function simpleDataIsCorrect()
    {
        $person = new Person(1, "Test Name", "Test Description", []);
        $this->assertEquals(["name" => "Test Name"], $person->getSimpleData());
    }

}
