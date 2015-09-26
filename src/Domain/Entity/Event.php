<?php

namespace Mikron\ReputationList\Domain\Entity;

class Event
{
    /**
     * @var int DB ID
     */
    private $dbId;

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
     * @param int $dBId
     * @param string $name
     * @param string $description
     */
    public function __construct($dBId, $name, $description)
    {
        $this->dbId = $dBId;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getDBId()
    {
        return $this->dbId;
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
