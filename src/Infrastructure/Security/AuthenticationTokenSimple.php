<?php

namespace Mikron\ReputationList\Infrastructure\Security;

use Mikron\ReputationList\Domain\Blueprint\AuthenticationToken;
use Mikron\ReputationList\Domain\Exception\AuthenticationException;
use Mikron\ReputationList\Domain\Exception\MissingComponentException;

/**
 * Class AuthenticationTokenSimple - simple key-based authentication
 * @package Mikron\ReputationList\Infrastructure\Security
 */
final class AuthenticationTokenSimple implements AuthenticationToken
{
    /**
     * @var string Key received from the outside
     */
    private $receivedKey;

    /**
     * @var string Key stored in configuration
     */
    private $correctKey;

    /**
     * AuthenticationToken constructor.
     * @param array $configAuthenticationSettings Configuration data for simple authentication strategy
     * @param string $key Key received from the outside
     * @throws AuthenticationException Thrown in case the key is invalid
     */
    public function __construct($configAuthenticationSettings, $key)
    {
        if ($this->isValid($key, 'received')) {
            $this->receivedKey = $key;
        }

        if (!isset($configAuthenticationSettings['simple'])) {
            throw new AuthenticationException(
                "Authentication configuration incorrect",
                "No configuration for simple authentication set"
            );
        }

        if (!isset($configAuthenticationSettings['simple']['authenticationKey'])) {
            throw new AuthenticationException(
                "Authentication configuration incorrect",
                "No authentication key for simple authentication set"
            );
        }

        if ($this->isValid($configAuthenticationSettings['simple']['authenticationKey'], 'internal')) {
            $this->correctKey = $configAuthenticationSettings['simple']['authenticationKey'];
        }
    }

    /**
     * @param string $key
     * @param string $identificationForErrors
     * @return bool
     * @throws AuthenticationException
     */
    private function isValid($key, $identificationForErrors)
    {
        if (empty($key)) {
            throw new AuthenticationException(
                "Authentication key incorrect",
                ucfirst($identificationForErrors) . " key must not be empty"
            );
        }

        if (strlen($key) < 20) {
            throw new AuthenticationException(
                "Authentication key incorrect",
                ucfirst($identificationForErrors) . " key is too short to be used"
            );
        }

        return true;
    }

    public function checksOut()
    {
        return $this->receivedKey == $this->correctKey;
    }
}
