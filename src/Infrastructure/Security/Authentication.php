<?php

namespace Mikron\ReputationList\Infrastructure\Security;

use Mikron\ReputationList\Domain\Blueprint\AuthenticationToken;
use Mikron\ReputationList\Domain\Exception\AuthenticationException;

/**
 * Class AuthenticationFactory
 * @package Mikron\ReputationList\Infrastructure\Security
 */
final class Authentication
{
    /**
     * @var AuthenticationToken
     */
    private $token;

    /**
     * @param array $config Configuration segment responsible for authentication
     * @param string $direction Who is trying to talk to us and which keyset is used?
     * @param string $authenticationMethodReceived What method are they trying to use?
     * @throws AuthenticationException
     */
    public function __construct($config, $direction, $authenticationMethodReceived)
    {
        if (!isset($config['authenticationMethodReference'])) {
            throw new AuthenticationException(
                "Authentication configuration error",
                "Authentication configuration error: missing reference table for authentication methods"
            );
        }

        if (!isset($config['authenticationMethodReference'][$authenticationMethodReceived])) {
            throw new AuthenticationException(
                "Authentication configuration error",
                "Authentication configuration error: missing reference for '$authenticationMethodReceived' method"
            );
        }

        $authenticationMethod = $config['authenticationMethodReference'][$authenticationMethodReceived];

        if (!in_array($authenticationMethod, $config[$direction]['allowedStrategies'])) {
            throw new AuthenticationException(
                "Authentication strategy '$authenticationMethod' ('$authenticationMethodReceived') not allowed"
            );
        }

        $this->token = $this->createToken($config[$direction], $authenticationMethod);
    }

    /**
     * @param array $configWithChosenDirection
     * @param string $authenticationMethod
     * @return AuthenticationToken
     * @throws AuthenticationException
     */
    private function createToken($configWithChosenDirection, $authenticationMethod)
    {
        $className = 'Mikron\ReputationList\Infrastructure\Security\AuthenticationToken' . ucfirst($authenticationMethod);

        if (!class_exists($className)) {
            throw new AuthenticationException(
                "Authentication configuration error",
                "Authentication configuration error: class $className, despite being allowed, does not exist"
            );
        }

        return new $className($configWithChosenDirection['settingsByStrategy']);
    }

    /**
     * @param string $authenticationKey What is the key they present?
     * @return bool
     */
    public function isAuthenticated($authenticationKey)
    {
        return $this->token->checksOut($authenticationKey);
    }

    /**
     * Provides authentication data for use in outgoing message
     * @return string[]
     */
    public function provideAuthenticationData()
    {
        return [
            'method' => 'auth-'.$this->token->provideMethod(),
            'key' => $this->token->provideKey()
        ];
    }
}
