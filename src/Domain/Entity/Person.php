<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Displayable;
use Mikron\ReputationList\Domain\ValueObject\StorageIdentification;

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
     * @var StorageIdentification
     */
    private $identification;

    /**
     * @var string Identifying string
     */
    private $name;

    /**
     * @var string Personal characteristic
     */
    private $description;

    /**
     * @var Reputation[] Reputations of the person
     */
    private $reputations;

    /**
     * Person constructor
     *
     * @param $identification
     * @param string $name
     * @param $description
     * @param Reputation[] $reputations
     */
    public function __construct($identification, $name, $description, array $reputations)
    {
        $this->identification = $identification;
        $this->name = $name;
        $this->description = $description;
        $this->reputations = $reputations;
    }

    /**
     * @return StorageIdentification
     */
    public function getIdentification()
    {
        return $this->identification;
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
     * @return string
     */
    public function getKey()
    {
        return $this->identification->getUuid();
    }

    /**
     * @return array Simple representation of the object content, fit for basic display
     */
    public function getSimpleData()
    {
        return [
            "name" => $this->getName(),
            "key" => $this->identification->getUuid(),
        ];
    }

    /**
     * @return array Complete representation of public parts of object content, fit for full card display
     */
    public function getCompleteData()
    {
        $data = [
            "name" => $this->getName(),
            "key" => $this->getKey(),
            "description" => $this->getDescription(),
            "reputations" => $this->getReputationCompleteData()
        ];

        return $data;
    }
}
