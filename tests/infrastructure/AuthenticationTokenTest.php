<?php

use Mikron\ReputationList\Infrastructure\Security\AuthenticationTokenSimple;

class AuthenticationTokenSimpleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function keyIsCorrect()
    {
        $token = new AuthenticationTokenSimple("0000000000000000000000000000000000000000");

        $expectation = "0000000000000000000000000000000000000000";

        $this->assertEquals($expectation, $token->getKey());
    }

    /**
     * @test
     */
    public function emptyTokenNotAllowed()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationTokenSimple("");
    }

    /**
     * @test
     */
    public function shortTokenNotAllowed()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationTokenSimple("SHORTY");
    }

    /**
     * @test
     */
    public function checksOutTrue()
    {
        $tokenMain = new AuthenticationTokenSimple("0000000000000000000000000000000000000000");
        $tokenReceived  = new AuthenticationTokenSimple("0000000000000000000000000000000000000000");

        $this->assertTrue($tokenMain->checksOut($tokenReceived));
    }

    /**
     * @test
     */
    public function checksOutFalse()
    {
        $tokenMain = new AuthenticationTokenSimple("0000000000000000000000000000000000000000");
        $tokenReceived  = new AuthenticationTokenSimple("0000000000000000000000000000000000000001");

        $this->assertFalse($tokenMain->checksOut($tokenReceived));
    }
}
