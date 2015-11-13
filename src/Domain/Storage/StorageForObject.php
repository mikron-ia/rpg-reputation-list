<?php

namespace Mikron\ReputationList\Domain\Storage;

interface StorageForObject
{
    public function retrieveById($dbId);
    public function retrieveByKey($key);
    public function retrieveAll();
}