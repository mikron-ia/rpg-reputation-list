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
     * @var string
     */
    private $name;

    /**
     * @var Reputation[]
     */
    private $reputations;

    /**
     * Person constructor.
     * @param string $name
     * @param Reputation[] $reputations
     */
    public function __construct($name, array $reputations)
    {
        $this->name = $name;
        $this->reputations = $reputations;
    }

}