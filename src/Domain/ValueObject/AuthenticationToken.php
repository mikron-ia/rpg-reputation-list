<?php

namespace Mikron\ReputationList\Domain\ValueObject;

use Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage;

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
     * @throws ExceptionWithSafeMessage
     */
    private function isValid($key)
    {
        if (empty($key)) {
            throw new ExceptionWithSafeMessage("Authentication key incorrect", "Key must not be empty");
        }

        return true;
    }
}
