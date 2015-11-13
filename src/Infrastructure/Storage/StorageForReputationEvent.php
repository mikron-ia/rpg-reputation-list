<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Mikron\ReputationList\Domain\Exception\KeyNotSupportedException;
use Mikron\ReputationList\Domain\Storage\StorageForObject;

/**
 * Class StorageForReputationEvent
 * @package Mikron\ReputationList\Infrastructure\Storage
 */
class StorageForReputationEvent implements StorageForObject
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * MySqlPerson constructor.
     * @param $storage
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    public function retrieveById($dbId)
    {
        $result = $this->storage->selectByPrimaryKey('reputation_event', 'reputation_event_id', [$dbId]);

        return $result;
    }

    public function retrieveAll()
    {
        $result = $this->storage->selectAll('reputation_event', 'reputation_event_id');

        return $result;
    }

    public function retrieveByPerson($personId)
    {
        $result = $this->storage->selectByKey('reputation_event', 'reputation_event_id', 'person_id', [$personId]);

        return $result;
    }

    public function retrieveByKey($key)
    {
        throw new KeyNotSupportedException("ReputationEvent does not support key use. Please use database ID");
    }
}
