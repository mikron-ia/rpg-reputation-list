<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Storage\StorageForObject;

final class StorageForPerson implements StorageForObject
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
        $result = $this->storage->selectByPrimaryKey('person', 'person_id', [$dbId]);

        return $result;
    }

    public function retrieveAll()
    {
        $result = $this->storage->selectAll('person', 'person_id');

        return $result;
    }

    public function retrieveByKey($key)
    {
        $result = $this->storage->selectByKey('person', 'person_id', 'key', [$key]);

        return $result;
    }

    public function retrieveByGroup($groupId)
    {
        $result = $this->storage->selectViaAssociation('person', 'group_members', 'person_id', 'group_id', [$groupId]);
        return $result;
    }

    public function countByGroup($groupId)
    {
        $result = $this->storage->countViaAssociation('person', 'group_members', 'person_id', 'group_id', [$groupId]);
        return $result;
    }
}
