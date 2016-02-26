<?php

namespace Mikron\ReputationList\Domain\ValueObject;

use Mikron\ReputationList\Domain\Blueprint\Displayable;

/**
 * Class representing influence extended on the reputation by another - be that group member's or another network
 *
 * @package Mikron\ReputationList\Domain\ValueObject
 */
final class ReputationInfluence implements Displayable
{
    /**
     * @var ReputationNetwork The network the influence is connected to
     */
    private $reputationNetwork;

    /**
     * @var int The value by which the reputation is influenced
     */
    private $value;

    /**
     * @param ReputationNetwork $reputationNetwork
     * @param int $value
     */
    public function __construct(ReputationNetwork $reputationNetwork, $value)
    {
        $this->reputationNetwork = $reputationNetwork;
        $this->value = $value;
    }

    /**
     * @return ReputationNetwork
     */
    public function getReputationNetwork()
    {
        return $this->reputationNetwork;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->reputationNetwork->getName();
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->reputationNetwork->getCode();
    }

    /**
     * @return array Simple representation of the object content, fit for basic display
     */
    public function getSimpleData()
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue()
        ];
    }

    /**
     * @return array Complete representation of public parts of object content, fit for full card display
     */
    public function getCompleteData()
    {
        return [
            'name' => $this->reputationNetwork->getName(),
            'code' => $this->reputationNetwork->getCode(),
            'value' => $this->getValue(),
        ];
    }
}
