<?php

use Mikron\ReputationList\Infrastructure\Security\AuthenticationTokenSimple;

class AuthenticationTokenSimpleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function emptyTokenNotAllowed()
    {
        $config = [
            'simple' => [
                'authenticationKey' => '0000000000000000000000000000000000000000',
            ],
        ];

        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationTokenSimple($config, "");
    }

    /**
     * @test
     */
    public function shortTokenNotAllowed()
    {
        $config = [
            'simple' => [
                'authenticationKey' => '0000000000000000000000000000000000000000',
            ],
        ];

        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationTokenSimple($config, "SHORTY");
    }

    /**
     * @test
     */
    public function noConfigNotAllowed()
    {
        $config = [];

        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationTokenSimple($config, "0000000000000000000000000000000000000000");
    }

    /**
     * @test
     */
    public function incompleteConfigNotAllowed()
    {
        $config = [
            'simple' => [],
        ];

        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationTokenSimple($config, "0000000000000000000000000000000000000000");
    }

    /**
     * @test
     */
    public function emptyKeyNotAllowed()
    {
        $config = [
            'simple' => [
                'authenticationKey' => '',
            ],
        ];

        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationTokenSimple($config, "0000000000000000000000000000000000000000");
    }

    /**
     * @test
     */
    public function shortKeyNotAllowed()
    {
        $config = [
            'simple' => [
                'authenticationKey' => 'SHORTY',
            ],
        ];

        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationTokenSimple($config, "0000000000000000000000000000000000000000");
    }

    /**
     * @test
     */
    public function checksOutTrue()
    {
        $config = [
            'simple' => [
                'authenticationKey' => '0000000000000000000000000000000000000000',
            ],
        ];

        $token = new AuthenticationTokenSimple($config, "0000000000000000000000000000000000000000");
        $this->assertTrue($token->checksOut());
    }

    /**
     * @test
     */
    public function checksOutFalse()
    {
        $config = [
            'simple' => [
                'authenticationKey' => '0000000000000000000000000000000000000000',
            ],
        ];

        $token = new AuthenticationTokenSimple($config, "0000000000000000000000000000000000000001");
        $this->assertFalse($token->checksOut());
    }
}
