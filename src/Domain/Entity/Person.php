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
    private $dbId;

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
     * @param int $dbId
     * @param string $name
     * @param Reputation[] $reputations
     */
    public function __construct($dbId, $name, array $reputations)
    {
        $this->dbId = $dbId;
        $this->name = $name;
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
     * @return Reputation[]
     */
    public function getReputations()
    {
        return $this->reputations;
    }

    /**
     * @return array
     */
    public function getSimpleIdentification()
    {
        return [
            "name" => $this->getName()
        ];
    }

}
