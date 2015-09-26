<?php

namespace Mikron\ReputationList\Domain\Entity;

/**
 * Class Person - describes a person that can have a reputation
 *
 * @package Mikron\ReputationList\Domain\Entity
 */
class Person
{
    /**
     * @var int DB ID
     */
    private $ID;

    /**
     * @var string Identifying string
     */
    private $name;

    /**
     * @var Reputation[] Reputations of the person
     */
    private $reputations;

    /**
     * Person constructor.
     * @param string $name
     * @param Reputation[] $reputations
     */
    public function __construct($ID, $name, array $reputations)
    {
        $this->ID = $ID;
        $this->name = $name;
        $this->reputations = $reputations;
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
     * @return Reputation[]
     */
    public function getReputations()
    {
        return $this->reputations;
    }

}