<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Mikron\ReputationList\Domain\Storage\PersonStorage;

final class MySqlPerson implements PersonStorage
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
        $result = $this->storage->selectByPrimaryKey('person', 'person_id', [$dbId]);

        return $result;
    }

    public function retrieveAll()
    {
        $result = $this->storage->selectAll('person', 'person_id');

        return $result;
    }
}
