<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

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

    public function retrieve($dbId)
    {
        $result = $this->storage->selectByPrimaryKey('reputation_event', 'reputation_event_id', [$dbId]);

        return $result;
    }

    public function retrieveAll()
    {
        $result = $this->storage->selectAll('reputation_event', 'reputation_event_id');

        return $result;
    }
}