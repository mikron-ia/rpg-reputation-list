<?php

use Mikron\ReputationList\Domain\Entity\Person;

class PersonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function nameIsDisplayedCorrectly()
    {
        $person = new Person("Test Name", []);
        $this->assertEquals("Test Name", $person->getName());
    }
}