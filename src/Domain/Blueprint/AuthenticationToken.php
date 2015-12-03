<?php

namespace Mikron\ReputationList\Domain\Blueprint;

interface AuthenticationToken
{
    /**
     * AuthenticationToken constructor - accepts key data
     *
     * @param array $configForMethod
     * @param string $key
     */
    public function __construct($configForMethod, $key);

    /**
     * Verifies if the token is valid
     * @return boolean true is token checks out
     */
    public function checksOut();
}
