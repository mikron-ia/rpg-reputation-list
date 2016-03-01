<?php

use Mikron\ReputationList\Domain\Entity\Group;
use Mikron\ReputationList\Domain\Entity\Person;
use Mikron\ReputationList\Domain\Entity\Reputation;
use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;
use Mikron\ReputationList\Infrastructure\Factory\StorageIdentification as StorageIdentificationFactory;

class GroupTest extends PHPUnit_Framework_TestCase
{
    private $identification;

    protected function setUp()
    {
        $idFactory = new StorageIdentificationFactory();
        $this->identification = $idFactory->createFromData(1, 'Test Key');
    }

    /**
     * @test
     */
    public function identificationIsCorrect()
    {
        $group = new Group($this->identification, 'Test Name', 'Test Description', [], [], []);
        $this->assertEquals($this->identification, $group->getIdentification());
    }

    /**
     * @test
     */
    public function nameIsCorrect()
    {
        $group = new Group($this->identification, 'Test Name', 'Test Description', [], [], []);
        $this->assertEquals('Test Name', $group->getName());
    }

    /**
     * @test
     * @dataProvider reputationDataProvider
     * @param $reputations
     */
    public function reputationsAreCorrectType($reputations)
    {
        $group = new Group($this->identification, 'Test Name', 'Test Description', $reputations, [], []);
        $this->assertContainsOnlyInstancesOf('Mikron\ReputationList\Domain\Entity\Reputation',
            $group->getReputations());
    }

    /**
     * @test
     */
    public function descriptionIsCorrect()
    {
        $group = new Group($this->identification, 'Test Name', 'Test Description', [], [], []);
        $this->assertEquals('Test Description', $group->getDescription());
    }

    /**
     * @test
     * @dataProvider memberDataProvider
     * @param $members
     */
    public function membersAreCorrectType($members)
    {
        $group = new Group($this->identification, 'Test Name', 'Test Description', [], [], $members);
        $this->assertContainsOnlyInstancesOf('Mikron\ReputationList\Domain\Entity\Person', $group->getMembers());
    }

    /**
     * @test
     * @dataProvider memberDataProvider
     * @param $members
     */
    public function memberCountIsCorrect($members)
    {
        $group = new Group($this->identification, 'Test Name', 'Test Description', [], [], $members);
        $this->assertEquals(count($members), $group->getMemberCount());
    }

    /**
     * @test
     * @depends nameIsCorrect
     */
    public function simpleDataIsCorrect()
    {
        $group = new Group($this->identification, 'Test Name', 'Test Description', [], [], []);

        $expected = [
            'name' => $group->getName(),
            'key' => $group->getKey(),
        ];

        $this->assertEquals($expected, $group->getSimpleData());
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
        $group = new Group($this->identification, 'Test Name', 'Test Description', $reputations, [], []);

        $expected = [
            'name' => $group->getName(),
            'key' => $group->getKey(),
            'description' => $group->getDescription(),
            'reputations' => $group->getReputationCompleteData(),
            'reputationEvents' => $group->getReputationEvents(),
            'membersReputations' => $group->getMemberCompleteData(),
        ];

        $this->assertEquals($expected, $group->getCompleteData());
    }

    public function reputationDataProvider()
    {
        $repNetCivil = new ReputationNetwork('c', ['name' => 'CivicNet', 'description' => 'Corporations']);
        $repNetMilitary = new ReputationNetwork('m', ['name' => 'MilNet', 'description' => 'Mercenaries']);
        $calculator = new \Mikron\ReputationList\Domain\Service\CalculatorGeneric();

        return [
            [
                [
                    new Reputation(
                        $repNetMilitary,
                        [
                            new ReputationEvent(null, $repNetMilitary, 2, null, 'action'),
                            new ReputationEvent(null, $repNetMilitary, 3, null, 'action'),
                        ],
                        [],
                        $calculator,
                        []
                    ),
                    new Reputation(
                        $repNetCivil,
                        [
                            new ReputationEvent(null, $repNetCivil, 1, null, 'action'),
                            new ReputationEvent(null, $repNetCivil, 2, null, 'action'),
                        ],
                        [],
                        $calculator,
                        []
                    )
                ]
            ],
            [
                [
                    new Reputation(
                        $repNetMilitary,
                        [
                            new ReputationEvent(null, $repNetMilitary, 2, null, 'action'),
                            new ReputationEvent(null, $repNetMilitary, 3, null, 'action'),
                        ],
                        [],
                        $calculator,
                        []
                    )
                ]
            ],
            [
                []
            ]
        ];
    }

    public function memberDataProvider()
    {
        return [
            [
                [],
            ],
            [
                [
                    new Person(null, "Test Person 0", "", [], [], 10),
                    new Person(null, "Test Person 1", "", [], [], 10)
                ],
            ],
            [
                [
                    new Person(null, "Test Person 0", "", [], [], 10),
                    new Person(null, "Test Person 1", "", [], [], 10),
                    new Person(null, "Test Person 2", "", [], [], 10),
                    new Person(null, "Test Person 3", "", [], [], 10),
                ],
            ],
        ];
    }
}
