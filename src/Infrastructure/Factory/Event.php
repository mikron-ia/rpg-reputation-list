<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Entity;
use Mikron\ReputationList\Domain\Exception\EventNotFoundException;
use Mikron\ReputationList\Infrastructure\Storage\StorageForEvent;

/**
 * Class Event
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
final class Event
{
    /**
     * @param int $dbId
     * @param $name
     * @param $description
     * @return Entity\Event
     */
    public function createFromSingleArray($dbId, $key, $name, $description)
    {
        $idFactory = new StorageIdentification();
        $identification = $idFactory->createFromData($dbId, $key);

        return new Entity\Event($identification, $name, $description);
    }

    /**
     * @param array $array
     * @return Entity\Event[]
     */
    public function createFromCompleteArray($array)
    {
        $list = [];

        if (!empty($array)) {
            foreach ($array as $record) {
                $list[] = $this->createFromSingleArray(
                    $record['event_id'],
                    $record['key'],
                    $record['name'],
                    $record['description']
                );
            }
        }

        return $list;
    }

    /**
     * Retrieves Event objects from database
     *
     * @param StorageEngine $connection
     * @return array
     */
    public function retrieveAllFromDb($connection)
    {
        $eventStorage = new StorageForEvent($connection);

        $array = $eventStorage->retrieveAll();

        if (!empty($array)) {
            foreach ($array as $record) {
                $list[] = $this->createFromSingleArray(
                    $record['event_id'],
                    $record['key'],
                    $record['name'],
                    $record['description']
                );
            }
        }

        return $list;
    }

    /**
     * @param StorageEngine $connection
     * @param int $dbId
     * @return Entity\Event
     * @throws EventNotFoundException
     */
    public function retrieveEventFromDbById($connection, $dbId)
    {
        $eventStorage = new StorageForEvent($connection);
        $eventWrapped = $eventStorage->retrieveById($dbId);
        $event = $this->unwrapEvent($eventWrapped, $dbId);

        return $event;
    }

    /**
     * @param StorageEngine $connection
     * @param $key
     * @return Entity\Event
     * @throws EventNotFoundException
     */
    public function retrieveEventFromDbByKey($connection, $key)
    {
        $eventStorage = new StorageForEvent($connection);
        $eventWrapped = $eventStorage->retrieveByKey($key);
        $event = $this->unwrapEvent($eventWrapped, $key);

        return $event;
    }

    /**
     * @param $eventWrapped
     * @param $identification
     * @return Entity\Event
     * @throws EventNotFoundException
     */
    public function unwrapEvent($eventWrapped, $identification)
    {
        if (!empty($eventWrapped)) {
            $eventUnwrapped = array_pop($eventWrapped);
            $event = $this->createFromSingleArray(
                $eventUnwrapped['event_id'],
                $eventUnwrapped['key'],
                $eventUnwrapped['name'],
                $eventUnwrapped['description']
            );
        } else {
            throw new EventNotFoundException(
                "Event with given identification has not been found in our database",
                "Event with given identification (" . $identification . ") has not been found in our database"
            );
        }

        return $event;
    }
}
