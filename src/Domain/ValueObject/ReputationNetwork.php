<?php

namespace Mikron\ReputationList\Domain\ValueObject;

use Mikron\ReputationList\Domain\Blueprint\Displayable;

/**
 * Class ReputationNetwork
 *
 * This value object describes a reputation network - a system where reputation is recorded, used, exchanged and modified
 * Data for ReputationNetwork are supposed to be loaded from config files and are not subject to change
 *
 * @package Mikron\ReputationList\Domain\ValueObject
 */
class ReputationNetwork implements Displayable
{
    private $name;
    private $code;
    private $description;

    public function __construct($key, $record)
    {
        $this->name = $record['name'];
        $this->code = isset($record['code'])?$record['code']:$key;
        $this->description = !empty($record['description'])?$record['description']:"[no description]";
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return array Simple representation of the object content, fit for basic display
     */
    public function getSimpleData()
    {
        return [
            "name" => $this->name,
            "code" => $this->code
        ];
    }

    /**
     * @return array Complete representation of public parts of object content, fit for full card display
     */
    public function getCompleteData()
    {
        return [
            "name" => $this->name,
            "code" => $this->code,
            "description" => $this->description
        ];
    }
}
