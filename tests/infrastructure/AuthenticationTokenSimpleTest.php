<?php

use Mikron\ReputationList\Infrastructure\Security\AuthenticationTokenSimple;

class AuthenticationTokenSimpleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function emptyTokenNotAllowed()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        AuthenticationTokenSimple::isValid("", "test");
    }

    /**
     * @test
     */
    public function shortTokenNotAllowed()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        AuthenticationTokenSimple::isValid("SHORTY", "test");
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

        $token = new AuthenticationTokenSimple($config);
        $this->assertTrue($token->checksOut("0000000000000000000000000000000000000000"));
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

        $token = new AuthenticationTokenSimple($config);
        $this->assertFalse($token->checksOut("0000000000000000000000000000000000000001"));
    }
}
