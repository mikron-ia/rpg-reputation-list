<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

/**
 * Class ReputationEvent
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
class ReputationEvent
{
    public function retrieveReputationEventsForPersonFromDb($connection, $personId)
    {
        $personStorage = new \Mikron\ReputationList\Infrastructure\Storage\MySqlPerson($connection);

        $personWrapped = $personStorage->retrieve($dbId);

        if (!empty($personWrapped)) {
            $personUnwrapped = array_pop($personWrapped);
            $person = $this->createFromSingleArray($personUnwrapped['dbId'], $personUnwrapped['name'], []);
        } else {
            $person = null;
        }

        return $person;
    }
}