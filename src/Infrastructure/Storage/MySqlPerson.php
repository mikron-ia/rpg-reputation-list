<?php

namespace Mikron\ReputationList\Infrastructure\Storage;

use Mikron\ReputationList\Domain\Storage\PersonStorage;

final class MySqlPerson implements PersonStorage
{
    /**
     * @var MySqlStorage
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
        // TODO: Implement retrieve() method.
    }

    public function retrieveAll()
    {
        $array = $this->storage->simpleSelect('person', []);


        return $array;
    }
}
