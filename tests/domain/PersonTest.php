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
     * @dataProvider reputationDataProvider
     * @param $reputations
     */
    public function areReputationsCorrectType($reputations)
    {
        $person = new Person(1, "Test Name", "Test Description", $reputations);
        $this->assertContainsOnlyInstancesOf("Mikron\\ReputationList\\Domain\\Entity\\Reputation",
            $person->getReputations());
    }

    /**
     * @test
     */
    public function simpleDataIsCorrect()
    {
        $person = new Person(1, "Test Name", "Test Description", []);
        $this->assertEquals(["name" => "Test Name"], $person->getSimpleData());
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
