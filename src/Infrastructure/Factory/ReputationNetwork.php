<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\ValueObject;

class ReputationNetwork
{
    public function createFromSingleArray($key, $data)
    {
        return new ValueObject\ReputationNetwork($key, $data);
    }

    public function createFromCompleteArray($array)
    {
        $reputationNetworkList = [];

        if (!empty($array)) {
            foreach ($array as $key => $record) {
                $reputationNetworkList[$key] = $this->createFromSingleArray($key, $record);
            }
        }

        return $reputationNetworkList;
    }
}
