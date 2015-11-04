<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

class StorageIdentification
{
    public function createFromArray($array)
    {
        return $this->createFromData($array['dbId'], $array['key']);
    }

    public function createFromData($dbId, $key)
    {
        return new \Mikron\ReputationList\Domain\ValueObject\StorageIdentification($dbId, $key);
    }
}