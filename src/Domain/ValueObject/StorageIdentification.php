<?php

namespace Mikron\ReputationList\Domain\ValueObject;

/**
 * Class StorageIdentification - wrapper for object identification
 *
 * @package Mikron\ReputationList\Domain\ValueObject
 */
final class StorageIdentification
{
    /**
     * @var int Database identifier
     */
    private $dbId;

    /**
     * @var string General unique identifier
     */
    private $uuid;

    /**
     * StorageIdentification constructor.
     * @param int $dbId
     * @param string $uuid
     */
    public function __construct($dbId, $uuid)
    {
        $this->dbId = $dbId;
        $this->uuid = $uuid;
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
    public function getUuid()
    {
        return $this->uuid;
    }
}
