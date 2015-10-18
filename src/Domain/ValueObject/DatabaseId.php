<?php

namespace Mikron\ReputationList\Domain\ValueObject;

/**
 * Class DatabaseId - wrapper for object's database ID
 * This class - and entire idea of wrapping primitives - is an experiment on my part, as I do not see a real reason
 * for it. If it proves useful, I will keep it, if not - it shall go.
 *
 * @package Mikron\ReputationList\Domain\ValueObject
 */
class DatabaseId
{
    private $dbId;

    /**
     * DatabaseId constructor.
     * @param $dbId
     */
    public function __construct($dbId)
    {
        $this->dbId = $dbId;
    }

    /**
     * @return int
     */
    public function getDbId()
    {
        return $this->dbId;
    }
}
