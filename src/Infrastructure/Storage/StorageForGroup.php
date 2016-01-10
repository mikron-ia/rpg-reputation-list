<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Storage\StorageForObject;

final class StorageForGroup implements StorageForObject
{
    /**
     * @var StorageEngine
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
        $result = $this->storage->selectByPrimaryKey('group', 'group_id', [$dbId]);

        return $result;
    }

    public function retrieveAll()
    {
        $result = $this->storage->selectAll('group', 'group_id');

        return $result;
    }

    public function retrieveByKey($key)
    {
        $result = $this->storage->selectByKey('group', 'group_id', 'key', [$key]);

        return $result;
    }
}
