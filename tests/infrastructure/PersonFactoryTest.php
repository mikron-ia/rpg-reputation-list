<?php

use Mikron\ReputationList\Infrastructure\Factory\Person;

class PersonFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Person
     */
    private $personFactory;

    protected function setUp()
    {
        $this->personFactory = new Person();
    }

    /**
     * @test
     * @dataProvider provideCorrectRow
     * @param $dbId
     * @param $key
     * @param $name
     * @param $description
     */
    public function arrayFactoryReturnsPerson($dbId, $key, $name, $description)
    {
        $person = $this->personFactory->createFromSingleArray($dbId, $key, $name, $description, []);
        $this->assertInstanceOf("Mikron\\ReputationList\\Domain\\Entity\\Person", $person);
    }

    public function provideCorrectRow()
    {
        return [
            [
                "person_id" => 1,
                "key" => "0000000000000000000000000000000000000000",
                "name" => "Test Person",
                "description" => "Test Description"
            ],
        ];
    }
}