<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Displayable;

/**
 * Class Event - describes event connected to reputation change; it should give some context & reason for the change
 * This class is intended to have a DB representation
 * Event is optional mechanic and MAY be included
 *
 * @package Mikron\ReputationList\Domain\Entity
 * @todo Consider reworking DB ID into a VO
 */
class Event implements Displayable
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

    /**
     * @return array Simple representation of the object content, fit for basic display
     */
    public function getSimpleData()
    {
        return [
            "name" => $this->getName()
        ];
    }

    /**
     * @return array Complete representation of public parts of object content, fit for full card display
     */
    public function getCompleteData()
    {
        return [
            "name" => $this->name,
            "description" => $this->description
        ];
    }
}
