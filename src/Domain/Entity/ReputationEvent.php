<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Displayable;
use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

/**
 * Class ReputationEvent - describes a specific change of reputation for a Person, resulting from Event, calculating into Reputation
 * This class is intended to have a DB representation
 *
 * @package Mikron\ReputationList\Domain\Entity
 * @todo Consider reworking DB ID into a VO
 */
class ReputationEvent implements Displayable
{
    /**
     * @var int DB ID
     */
    private $dbId;

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
     * @param int $dbId
     * @param ReputationNetwork $reputationNetwork
     * @param int $value
     * @param Event $event
     */
    public function __construct($dbId, ReputationNetwork $reputationNetwork, $value, $event)
    {
        $this->dbId = $dbId;
        $this->reputationNetwork = $reputationNetwork;
        $this->value = $value;
        $this->event = !empty($event) ? $event : null;
    }

    /**
     * @return int
     */
    public function getDbId()
    {
        return $this->dbId;
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->reputationNetwork->getName();
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->reputationNetwork->getCode();
    }

    /**
     * @return array Simple representation of the object content, fit for basic display
     */
    public function getSimpleData()
    {
        return [
            "name" => $this->getName(),
            "value" => $this->getValue()
        ];
    }

    /**
     * @return array Complete representation of public parts of object content, fit for full card display
     */
    public function getCompleteData()
    {
        return [
            "name" => $this->reputationNetwork->getName(),
            "code" => $this->reputationNetwork->getCode(),
            "value" => $this->getValue(),
            "event" => isset($this->event)?$this->event->getCompleteData():null,
        ];
    }
}
