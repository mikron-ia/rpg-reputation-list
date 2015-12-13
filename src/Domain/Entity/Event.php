<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Displayable;
use Mikron\ReputationList\Domain\ValueObject\StorageIdentification;

/**
 * Class Event - describes event connected to reputation change; it should give some context & reason for the change
 * This class is intended to have a DB representation
 * Event is optional mechanic and MAY be included
 *
 * @package Mikron\ReputationList\Domain\Entity
 */
final class Event implements Displayable
{
    /**
     * @var StorageIdentification
     */
    private $identification;

    /**
     * @var string
     */
    private $time;

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
     * @param StorageIdentification $identification
     * @param string $time
     * @param string $name
     * @param string $description
     */
    public function __construct($identification, $time, $name, $description)
    {
        $this->identification = $identification;
        $this->time = $time;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return StorageIdentification
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * @return int
     */
    public function getDbId()
    {
        return $this->identification->getDbId();
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
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
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "time" => $this->getTime(),
        ];
    }
}
