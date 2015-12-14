<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Entity\Event;
use Mikron\ReputationList\Domain\Entity\ReputationEvent as ReputationEventEntity;
use Mikron\ReputationList\Domain\Exception\RecordNotFoundException;
use Mikron\ReputationList\Infrastructure\Factory\Event as EventFactory;
use Mikron\ReputationList\Infrastructure\Factory\StorageIdentification as StorageIdentificationFactory;
use Mikron\ReputationList\Infrastructure\Storage\StorageForReputationEvent;
use Psr\Log\LoggerInterface;

/**
 * Class ReputationEvent
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
final class ReputationEvent
{
    /**
     * @param $reputationNetworksList
     * @param int $dbId
     * @param string $reputationNetworkCode
     * @param int $change
     * @param Event $event
     * @return ReputationEventEntity
     * @throws RecordNotFoundException
     */
    public function createFromSingleArray(
        $reputationNetworksList,
        $dbId,
        $reputationNetworkCode,
        $change,
        $event
    )
    {
        if (isset($reputationNetworksList[$reputationNetworkCode])) {
            $reputationNetwork = $reputationNetworksList[$reputationNetworkCode];
        } else {
            throw new RecordNotFoundException(
                "Reputation not found",
                "Record with DB ID == " . $dbId . " has an unrecognised reputation code: " . $reputationNetworkCode
            );
        }

        $idFactory = new StorageIdentificationFactory();
        $identification = $idFactory->createFromData($dbId, null);

        return new ReputationEventEntity($identification, $reputationNetwork, $change, $event);
    }

    /**
     * @param StorageEngine $connection
     * @param LoggerInterface $logger
     * @param $reputationNetworksList
     * @param int $personId
     * @return ReputationEventEntity[]
     * @throws RecordNotFoundException
     */
    public function retrieveReputationEventsForPersonFromDb($connection, $logger, $reputationNetworksList, $personId)
    {
        $repEventsStorage = new StorageForReputationEvent($connection);
        $repEventsWrapped = $repEventsStorage->retrieveByPerson($personId);

        $repEvents = [];

        if (!empty($repEventsWrapped)) {
            $eventStorage = new EventFactory();
            foreach ($repEventsWrapped as $repEventUnwrapped) {
                try {
                    if (isset($repEventUnwrapped['event_id'])) {
                        $event = $eventStorage->retrieveEventFromDbById($connection, $repEventUnwrapped['event_id']);
                    } else {
                        $event = null;
                    }

                    $repEvents[] = $this->createFromSingleArray(
                        $reputationNetworksList,
                        $repEventUnwrapped['reputation_event_id'],
                        $repEventUnwrapped['rep_network_code'],
                        $repEventUnwrapped['change'],
                        $event
                    );
                } catch (RecordNotFoundException $e) {
                    $logger->error(
                        "Source data error (RecordNotFoundException): " . $e->getMessage(),
                        ['exception' => $e]
                    );
                }
            }
        }

        return $repEvents;
    }
}
