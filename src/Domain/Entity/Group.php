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
     * @param array $reputations
     * @param Person[] $members
     */
    public function __construct($identification, $name, $description, array $reputations, array $members)
    {
        parent::__construct($identification, $name, $description, $reputations);
        $this->members = $members;
    }
}
