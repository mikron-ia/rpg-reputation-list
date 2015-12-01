<?php

use Mikron\ReputationList\Domain\ValueObject\AuthenticationToken;

class AuthenticationTokenTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function keyIsCorrect()
    {
        $token = new AuthenticationToken("0000000000000000000000000000000000000000");

        $expectation = "0000000000000000000000000000000000000000";

        $this->assertEquals($expectation, $token->getKey());
    }

    /**
     * @test
     */
    public function emptyTokenNotAllowed()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationToken("");
    }

    /**
     * @test
     */
    public function shortTokenNotAllowed()
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\AuthenticationException');
        new AuthenticationToken("SHORTY");
    }
}
