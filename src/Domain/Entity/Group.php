<?php

namespace Mikron\ReputationList\Domain\Entity;

/**
 * Class Group
 * @package Mikron\ReputationList\Domain\Entity
 */
class Group extends Person
{
    /**
     * @var Person[]
     */
    private $members;

    /**
     * Group constructor.
     * @param $identification
     * @param string $name
     * @param $description
     * @param Reputation[] $reputations
     * @param ReputationEvent[] $reputationEvents
     * @param Person[] $members
     */
    public function __construct($identification, $name, $description, $reputations, $reputationEvents, $members)
    {
        parent::__construct($identification, $name, $description, $reputations, $reputationEvents, 0);
        $this->members = $members;
    }

    /**
     * @return Person[]
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @return array Members exported to completed data form
     */
    public function getMemberCompleteData()
    {
        $members = [];

        foreach ($this->members as $member) {
            $members[] = $member->getCompleteData();
        }

        return $members;
    }

    /**
     * @return int Number of members
     */
    public function getMemberCount()
    {
        return count($this->members);
    }

    /**
     * @return array Simple representation of the object content, fit for basic display
     */
    public function getSimpleData()
    {
        return parent::getSimpleData();
    }

    /**
     * @return array Complete representation of public parts of object content, fit for full card display
     */
    public function getCompleteData()
    {
        $data = parent::getCompleteData();
        $data['members'] = $this->getMemberCompleteData();

        return $data;
    }
}
