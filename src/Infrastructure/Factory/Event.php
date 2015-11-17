<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Entity;
use Mikron\ReputationList\Infrastructure\Storage\StorageForEvent;

class Event
{
    /**
     * @param int $dbId
     * @param $name
     * @param $description
     * @return Entity\Event
     */
    public function createFromSingleArray($dbId, $name, $description)
    {
        $idFactory = new StorageIdentification();
        $identification = $idFactory->createFromData($dbId, null);

        return new Entity\Event(
            $identification,
            $name,
            $description
        );
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
                $list[] = $this->createFromSingleArray($record['event_id'], $record['name'], $record['description']);
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

        if (!empty($eventWrapped)) {
            $eventUnwrapped = array_pop($eventWrapped);

            $event = $this->createFromSingleArray(
                $eventUnwrapped['event_id'],
                $eventUnwrapped['name'],
                $eventUnwrapped['description']
            );
        } else {
            throw new EventNotFoundException("Event with given ID has not been found in our database");
        }

        return $event;
    }
}
