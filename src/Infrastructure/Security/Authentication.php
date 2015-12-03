<?php

namespace Mikron\ReputationList\Infrastructure\Security;

use Mikron\ReputationList\Domain\Blueprint\AuthenticationToken;

/**
 * Class AuthenticationFactory
 * @package Mikron\ReputationList\Infrastructure\Security
 */
class Authentication
{
    /**
     * @param array $config
     * @param string $authenticationMethod
     * @param string $authenticationKey
     */
    public function __construct($config, $authenticationMethod, $authenticationKey)
    {
    }

    /**
     * @param array $config
     * @param string $authenticationMethod
     * @param string $authenticationKey
     * @return AuthenticationToken
     */
    private function createToken($config, $authenticationMethod, $authenticationKey)
    {

    }

    public function isAuthenticated()
    {

    }
}
