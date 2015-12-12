<?php

use Mikron\ReputationList\Domain\Entity\Person;
use Mikron\ReputationList\Domain\Entity\Reputation;
use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

class PersonTest extends PHPUnit_Framework_TestCase
{
    private $identification;

    protected function setUp()
    {
        $idFactory = new \Mikron\ReputationList\Infrastructure\Factory\StorageIdentification();
        $this->identification = $idFactory->createFromData(1, 'Test Key');
    }

    /**
     * @test
     */
    public function identificationIsCorrect()
    {
        $person = new Person($this->identification, 'Test Name', 'Test Description', [], []);
        $this->assertEquals($this->identification, $person->getIdentification());
    }

    /**
     * @test
     */
    public function nameIsCorrect()
    {
        $person = new Person($this->identification, 'Test Name', 'Test Description', [], []);
        $this->assertEquals('Test Name', $person->getName());
    }

    /**
     * @test
     * @dataProvider reputationDataProvider
     * @param $reputations
     */
    public function reputationsAreCorrectType($reputations)
    {
        $person = new Person($this->identification, 'Test Name', 'Test Description', $reputations, []);
        $this->assertContainsOnlyInstancesOf('Mikron\ReputationList\Domain\Entity\Reputation',
            $person->getReputations());
    }

    /**
     * @test
     */
    public function descriptionIsCorrect()
    {
        $person = new Person($this->identification, 'Test Name', 'Test Description', [], []);
        $this->assertEquals('Test Description', $person->getDescription());
    }

    /**
     * @test
     * @depends nameIsCorrect
     */
    public function simpleDataIsCorrect()
    {
        $person = new Person($this->identification, 'Test Name', 'Test Description', [], []);

        $expected = [
            'name' => $person->getName(),
            'key' => $person->getKey(),
        ];

        $this->assertEquals($expected, $person->getSimpleData());
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
        $person = new Person($this->identification, 'Test Name', 'Test Description', $reputations, []);

        $expected = [
            'name' => $person->getName(),
            'key' => $person->getKey(),
            'description' => $person->getDescription(),
            'reputations' => $person->getReputationCompleteData(),
            'reputationEvents' => $person->getReputationEvents()
        ];

        $this->assertEquals($expected, $person->getCompleteData());
    }

    public function reputationDataProvider()
    {
        $repNetCivil = new ReputationNetwork('c', ['name' => 'CivicNet', 'description' => 'Corporations']);
        $repNetMilitary = new ReputationNetwork('m', ['name' => 'MilNet', 'description' => 'Mercenaries']);
        $calculators = [];
        
        return [
            [
                [
                    new Reputation(
                        $repNetMilitary,
                        [
                            new ReputationEvent(null, $repNetMilitary, 2, null),
                            new ReputationEvent(null, $repNetMilitary, 3, null),
                        ],
                        $calculators
                    ),
                    new Reputation(
                        $repNetCivil,
                        [
                            new ReputationEvent(null, $repNetCivil, 1, null),
                            new ReputationEvent(null, $repNetCivil, 2, null),
                        ],
                        $calculators
                    )
                ]
            ],
            [
                [
                    new Reputation(
                        $repNetMilitary,
                        [
                            new ReputationEvent(null, $repNetMilitary, 2, null),
                            new ReputationEvent(null, $repNetMilitary, 3, null),
                        ],
                        $calculators
                    )
                ]
            ],
            [
                []
            ]
        ];
    }
}
