<?php

use Mikron\ReputationList\Domain\Entity\Reputation;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

class ReputationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function reputationIsCorrect()
    {
        $repNet = new ReputationNetwork("m", ["name" => "MilNet", "description" => "Mercenaries"]);
        $reputation = new Reputation($repNet, []);
        $this->assertEquals($repNet, $reputation->getReputationNetwork());
    }

}
