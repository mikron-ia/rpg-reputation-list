<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\Blueprint\StorageEngine;
use Mikron\ReputationList\Domain\Entity\Event;
use Mikron\ReputationList\Domain\Entity\ReputationEvent as ReputationEventEntity;
use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;
use Mikron\ReputationList\Domain\Exception\RecordNotFoundException;
use Mikron\ReputationList\Infrastructure\Factory\StorageIdentification as StorageIdentificationFactory;
use Mikron\ReputationList\Infrastructure\Storage\StorageForReputationEvent;

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
     * @param \Monolog\Logger $logger
     * @param $reputationNetworksList
     * @param int $personId
     * @return array
     * @throws RecordNotFoundException
     */
    public function retrieveReputationEventsForPersonFromDb($connection, $logger, $reputationNetworksList, $personId)
    {
        $repEventsStorage = new StorageForReputationEvent($connection);
        $repEventsWrapped = $repEventsStorage->retrieveByPerson($personId);

        $repEvents = [];

        if (!empty($repEventsWrapped)) {
            foreach ($repEventsWrapped as $repEventUnwrapped) {
                try {
                    $repEvents[] = $this->createFromSingleArray(
                        $reputationNetworksList,
                        $repEventUnwrapped['reputation_event_id'],
                        $repEventUnwrapped['rep_network_code'],
                        $repEventUnwrapped['change'],
                        null
                    );
                } catch(RecordNotFoundException $e) {
                    $logger->addWarning("Source data error (RecordNotFoundException): ".$e->getMessage());
                }
            }
        }

        return $repEvents;
    }
}
