<?php

namespace Mikron\ReputationList\Domain\Storage;

interface StorageForObject
{
    public function retrieve($dbId);
    public function retrieveAll();
}