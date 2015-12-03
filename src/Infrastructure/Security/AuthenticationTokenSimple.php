<?php

namespace Mikron\ReputationList\Infrastructure\Security;

use Mikron\ReputationList\Domain\Blueprint\AuthenticationToken;
use Mikron\ReputationList\Domain\Exception\AuthenticationException;

/**
 * Class AuthenticationTokenSimple - simple key-based authentication
 * @package Mikron\ReputationList\Infrastructure\Security
 */
final class AuthenticationTokenSimple implements AuthenticationToken
{
    /**
     * @var string
     */
    private $key;

    /**
     * AuthenticationToken constructor.
     * @param string $key
     */
    public function __construct($key)
    {
        if($this->isValid($key)) {
            $this->key = $key;
        }
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $key
     * @return bool
     * @throws AuthenticationException
     */
    private function isValid($key)
    {
        if (empty($key)) {
            throw new AuthenticationException("Authentication key incorrect", "Key must not be empty");
        }

        if (strlen($key) < 20) {
            throw new AuthenticationException("Authentication key incorrect", "Key is too short to be used");
        }

        return true;
    }

    public function checksOut($token)
    {
        return $this->key == $token->getKey();
    }
}
