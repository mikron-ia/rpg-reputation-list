<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Entity;

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


}