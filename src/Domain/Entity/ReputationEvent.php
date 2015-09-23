<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\ValueObject\ReputationNetwork;

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
     * @param DB $ID
     * @param ReputationNetwork $reputationNetwork
     * @param int $value
     * @param Event $event
     */
    public function __construct($ID, ReputationNetwork $reputationNetwork, $value, Event $event)
    {
        $this->ID = $ID;
        $this->reputationNetwork = $reputationNetwork;
        $this->value = $value;
        $this->event = !empty($event)?$event:null;
    }

}