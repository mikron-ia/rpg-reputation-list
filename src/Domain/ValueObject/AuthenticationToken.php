<?php

namespace Mikron\ReputationList\Domain\ValueObject;

use Mikron\ReputationList\Domain\Exception\AuthenticationException;

final class AuthenticationToken
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
}
