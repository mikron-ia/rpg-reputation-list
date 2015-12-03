<?php

namespace Mikron\ReputationList\Infrastructure\Security;

use Mikron\ReputationList\Domain\Blueprint\AuthenticationToken;
use Mikron\ReputationList\Domain\Exception\AuthenticationException;

/**
 * Class AuthenticationFactory
 * @package Mikron\ReputationList\Infrastructure\Security
 */
class Authentication
{
    /**
     * @var AuthenticationToken
     */
    private $token;

    /**
     * @param array $config
     * @param string $authenticationMethod
     * @param string $authenticationKey
     * @throws AuthenticationException
     */
    public function __construct($config, $authenticationMethod, $authenticationKey)
    {
        if (!in_array($authenticationMethod, $config['allowedStrategies'])) {
            throw new AuthenticationException("Authentication strategy $authenticationMethod not allowed");
        }

        $this->token = $this->createToken($config, $authenticationMethod, $authenticationKey);
    }

    /**
     * @param array $config
     * @param string $authenticationMethod
     * @param string $authenticationKey
     * @return AuthenticationToken
     * @throws AuthenticationException
     */
    private function createToken($config, $authenticationMethod, $authenticationKey)
    {
        $className = 'Mikron\ReputationList\Infrastructure\Security\AuthenticationToken' . ucfirst($config['reference'][$authenticationMethod]);

        if (!class_exists($className)) {
            throw new AuthenticationException(
                "Authentication configuration error",
                "Authentication configuration error: class $className, despite being allowed, does not exist"
            );
        }

        return new $className($authenticationKey);
    }

    public function isAuthenticated()
    {
        return $this->token->checksOut();
    }
}
