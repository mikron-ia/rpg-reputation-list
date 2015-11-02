<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Displayable;

/**
 * Class Person - describes a person that can have a reputation
 * This class is intended to have a DB representation
 *
 * @package Mikron\ReputationList\Domain\Entity
 * @todo Add some description component
 */
final class Person implements Displayable
{
    /**
     * @var int DB ID
     */
    private $dbId;

    /**
     * @var string Identifying string
     */
    private $name;

    /**
     * @var string Identifying string
     */
    private $key;

    /**
     * @var string Personal characteristic
     */
    private $description;

    /**
     * @var Reputation[] Reputations of the person
     */
    private $reputations;

    /**
     * Person constructor.
     * @param int $dbId
     * @param string $name
     * @param $key
     * @param string $description
     * @param Reputation[] $reputations
     */
    public function __construct($dbId, $name, $key, $description, array $reputations)
    {
        $this->dbId = $dbId;
        $this->name = $name;
        $this->key = $key;
        $this->description = $description;
        $this->reputations = $reputations;
    }

    /**
     * @return int
     */
    public function getDbId()
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
     * @return Reputation[]
     */
    public function getReputations()
    {
        return $this->reputations;
    }

    /**
     * @return Reputation[]
     */
    public function getReputationCompleteData()
    {
        $reps = [];
        foreach ($this->reputations as $reputation) {
            $reps['reputations'][] = $reputation->getCompleteData();
        }
        return $reps;
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
        $data = [
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "reputations" => $this->getReputationCompleteData()
        ];

        return $data;
    }
}
