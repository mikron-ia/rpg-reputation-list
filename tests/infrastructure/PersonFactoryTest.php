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
     * @param $reputations
     */
    public function arrayFactoryReturnsPerson($dbId, $key, $name, $description, $reputations)
    {
        $person = $this->personFactory->createFromSingleArray($dbId, $key, $name, $description, $reputations);
        $this->assertInstanceOf("Mikron\\ReputationList\\Domain\\Entity\\Person", $person);
    }

    /**
     * @test
     * @dataProvider provideCorrectArray
     * @param $array
     */
    public function massiveFactoryReturnsPersons($array)
    {
        $people = $this->personFactory->createFromCompleteArray($array);
        $this->assertContainsOnlyInstancesOf("Mikron\\ReputationList\\Domain\\Entity\\Person", $people);
    }

    public function provideCorrectRow()
    {
        return [
            [
                "person_id" => 1,
                "key" => "0000000000000000000000000000000000000000",
                "name" => "Test Person",
                "description" => "Test Description",
                []
            ],
        ];
    }

    public function provideCorrectArray()
    {
        return [
            [
                [
                    [
                        "person_id" => 1,
                        "key" => "0000000000000000000000000000000000000001",
                        "name" => "Test Person",
                        "description" => "Test Description",
                        []
                    ],
                    [
                        "person_id" => 2,
                        "key" => "0000000000000000000000000000000000000002",
                        "name" => "Test Person",
                        "description" => "Test Description",
                        []
                    ],
                    [
                        "person_id" => 3,
                        "key" => "0000000000000000000000000000000000000003",
                        "name" => "Test Person",
                        "description" => "Test Description",
                        []
                    ],
                    [
                        "person_id" => 4,
                        "key" => "0000000000000000000000000000000000000004",
                        "name" => "Test Person",
                        "description" => "Test Description",
                        []
                    ],
                    [
                        "person_id" => 5,
                        "key" => "0000000000000000000000000000000000000005",
                        "name" => "Test Person",
                        "description" => "Test Description",
                        []
                    ],
                ]
            ]
        ];
    }
}
