<?php

use Mikron\ReputationList\Domain\Entity\Person;
use Mikron\ReputationList\Domain\Entity\Reputation;
use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

class PersonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function idIsCorrect()
    {
        $person = new Person(1, "Test Name", "Test Key", "Test Description", []);
        $this->assertEquals(1, $person->getDBId());
    }

    /**
     * @test
     */
    public function nameIsCorrect()
    {
        $person = new Person(1, "Test Name", "Test Key", "Test Description", []);
        $this->assertEquals("Test Name", $person->getName());
    }

    /**
     * @test
     * @dataProvider reputationDataProvider
     * @param $reputations
     */
    public function reputationsAreCorrectType($reputations)
    {
        $person = new Person(1, "Test Name", "Test Key", "Test Description", $reputations);
        $this->assertContainsOnlyInstancesOf("Mikron\\ReputationList\\Domain\\Entity\\Reputation",
            $person->getReputations());
    }

    /**
     * @test
     */
    public function descriptionIsCorrect()
    {
        $person = new Person(1, "Test Name", "Test Key", "Test Description", []);
        $this->assertEquals("Test Description", $person->getDescription());
    }

    /**
     * @test
     * @depends nameIsCorrect
     */
    public function simpleDataIsCorrect()
    {
        $person = new Person(1, "Test Name", "Test Key", "Test Description", []);
        $this->assertEquals(["name" => $person->getName()], $person->getSimpleData());
    }

    /**
     * @test
     * @depends      nameIsCorrect
     * @depends      descriptionIsCorrect
     * @depends      reputationsAreCorrectType
     * @dataProvider reputationDataProvider
     * @param $reputations
     */
    public function completeDataIsCorrect($reputations)
    {
        $person = new Person(1, "Test Name", "Test Key", "Test Description", $reputations);

        $expected = [
            "name" => $person->getName(),
            "description" => $person->getDescription(),
            "reputations" => $person->getReputationCompleteData()
        ];

        $this->assertEquals($expected, $person->getCompleteData());
    }

    public function reputationDataProvider()
    {
        $repNetCivil = new ReputationNetwork("c", ["name" => "CivicNet", "description" => "Corporations"]);
        $repNetMilitary = new ReputationNetwork("m", ["name" => "MilNet", "description" => "Mercenaries"]);

        return [
            [
                [
                    new Reputation($repNetMilitary, [
                        new ReputationEvent(null, $repNetMilitary, 2, null),
                        new ReputationEvent(null, $repNetMilitary, 3, null),
                    ]),
                    new Reputation($repNetCivil, [
                        new ReputationEvent(null, $repNetCivil, 1, null),
                        new ReputationEvent(null, $repNetCivil, 2, null),
                    ])
                ]
            ],
            [
                [
                    new Reputation($repNetMilitary, [
                        new ReputationEvent(null, $repNetMilitary, 2, null),
                        new ReputationEvent(null, $repNetMilitary, 3, null),
                    ])
                ]
            ],
            [
                []
            ]
        ];
    }

}
