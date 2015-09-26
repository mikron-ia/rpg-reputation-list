<?php

namespace Mikron\ReputationList\Domain\Entity;

class Event
{
    /**
     * @var int DB ID
     */
    private $ID;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * Event constructor.
     * @param int $ID
     * @param string $name
     * @param string $description
     */
    public function __construct($ID, $name, $description)
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

}