<?php

class ExceptionWithSafeMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider messages
     *
     * @param $safeMessage
     * @param $message
     * @param $expectedSafeMessage
     * @param $expectedMessage
     */
    public function safeMessageSetCorrectly($safeMessage, $message, $expectedSafeMessage, $expectedMessage)
    {
        $exception = new \Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage($safeMessage, $message);
        $this->assertEquals($expectedSafeMessage, $exception->getSafeMessage());
    }

    /**
     * @test
     * @dataProvider messages
     *
     * @param $safeMessage
     * @param $message
     * @param $expectedSafeMessage
     * @param $expectedMessage
     */
    public function messageFallbackWorks($safeMessage, $message, $expectedSafeMessage, $expectedMessage)
    {
        $exception = new \Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage($safeMessage, $message);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    public function messages()
    {
        return [
            [
                "Safe message",
                "Unsafe message",
                "Safe message",
                "Unsafe message",
            ],
            [
                "Safe message",
                null,
                "Safe message",
                "Safe message",
            ],
        ];
    }
}
