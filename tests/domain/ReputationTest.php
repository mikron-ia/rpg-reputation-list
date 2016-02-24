<?php

use Mikron\ReputationList\Domain\Entity\Reputation;
use Mikron\ReputationList\Domain\Entity\ReputationEvent;
use Mikron\ReputationList\Domain\Service\CalculatorGeneric;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;
use Mikron\ReputationList\Domain\ValueObject\StorageIdentification;

class ReputationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider correctReputationNetworkProvider
     * @param $repNet
     * @param $repEvents
     */
    public function reputationNetworkIsCorrect($repNet, $repEvents)
    {
        $reputation = new Reputation($repNet, $repEvents, new CalculatorGeneric(), []);
        $this->assertEquals($repNet, $reputation->getReputationNetwork());
    }

    /**
     * @test
     * @dataProvider correctReputationNetworkProvider
     * @param $repNet
     * @param $repEvents
     */
    public function simpleDataIsCorrect($repNet, $repEvents)
    {
        $reputation = new Reputation($repNet, $repEvents, new CalculatorGeneric(), []);

        $expectation = [
            'name' => $reputation->getName(),
            'code' => $reputation->getCode(),
            'value' => $reputation->getValue(),
        ];

        $this->assertEquals($expectation, $reputation->getSimpleData());
    }

    /**
     * @test* @dataProvider correctReputationNetworkProvider
     * @param $repNet
     * @param $repEvents
     */
    public function completeDataIsCorrect($repNet, $repEvents)
    {
        $reputation = new Reputation($repNet, $repEvents, new CalculatorGeneric(), []);

        $expectation = [
            'name' => $reputation->getName(),
            'code' => $reputation->getCode(),
            'description' => $reputation->getDescription(),
            'value' => $reputation->getValues([])
        ];

        $this->assertEquals($expectation, $reputation->getCompleteData());
    }

    /**
     * @test
     * @dataProvider correctReputationNetworkProvider
     * @param $repNet
     * @param $repEvents
     */
    public function reputationEventsAreOfCorrectClass($repNet, $repEvents)
    {
        $reputation = new Reputation($repNet, $repEvents, new CalculatorGeneric(), []);
        $className = 'Mikron\\ReputationList\\Domain\\Entity\\ReputationEvent';

        $this->assertContainsOnlyInstancesOf($className, $reputation->getReputationEvents());
    }

    public function correctReputationNetworkProvider()
    {
        $reputationNetwork = new ReputationNetwork('c', ['name' => 'CivicNet', 'description' => 'Corporations']);

        return [
            [
                $reputationNetwork,
                []
            ],
            [
                $reputationNetwork,
                [
                    new ReputationEvent(new StorageIdentification(1, null), $reputationNetwork, 5, null)
                ]
            ],
            [
                $reputationNetwork,
                [
                    new ReputationEvent(new StorageIdentification(1, null), $reputationNetwork, 5, null),
                    new ReputationEvent(new StorageIdentification(2, null), $reputationNetwork, 2, null)
                ]
            ],
        ];
    }
}
