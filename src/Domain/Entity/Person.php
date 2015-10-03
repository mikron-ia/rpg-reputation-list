<?php

namespace Mikron\ReputationList\Domain\Entity;

use Mikron\ReputationList\Domain\Blueprint\Displayable;

/**
 * Class Person - describes a person that can have a reputation
 * This class is intended to have a DB representation
 *
 * @package Mikron\ReputationList\Domain\Entity
 * @todo Add some description component
 * @todo Consider adding UUID for communication with other services
 * @todo Consider reworking DB ID into a VO
 */
class Person implements Displayable
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
            "reputations" => [],
        ];

        foreach ($this->reputations as $reputation) {
            $data['reputations'][] = $reputation->getCompleteData();
        }

        return $data;
    }
}
