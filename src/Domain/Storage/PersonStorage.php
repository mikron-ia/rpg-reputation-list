<?php

namespace Mikron\ReputationList\Domain\Storage;

interface PersonStorage
{
    public function retrieve($dbId);
    public function retrieveAll();
}