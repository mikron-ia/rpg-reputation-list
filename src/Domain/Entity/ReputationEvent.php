<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

/**
 * Class ReputationEvent - describes a specific change of reputation for a Person, resulting from Event, calculating into Reputation
 * This class is intended to have a DB representation
 *
 * @package Mikron\ReputationList\Domain\Entity
 */
class ReputationEvent
{
    /**
     * @var int DB ID
     */
    private $ID;

    /**
     * @var ReputationNetwork The network the event is connected to
     */
    private $reputationNetwork;

    /**
     * @var int The value by which the reputation changed
     */
    private $value;

    /**
     * @var Event The optional event the reputation event came from
     */
    private $event;

    /**
     * ReputationEvent constructor.
     * @param int $ID
     * @param ReputationNetwork $reputationNetwork
     * @param int $value
     * @param Event $event
     */
    public function __construct($ID, ReputationNetwork $reputationNetwork, $value, Event $event)
    {
        $this->ID = $ID;
        $this->reputationNetwork = $reputationNetwork;
        $this->value = $value;
        $this->event = !empty($event) ? $event : null;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @return ReputationNetwork
     */
    public function getReputationNetwork()
    {
        return $this->reputationNetwork;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

}
