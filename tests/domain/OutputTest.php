<?php

use Mikron\ReputationList\Domain\Service\Output;

class OutputTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function packIsCorrect()
    {
        $output = new Output("Test title", "Test description", ["alfa" => "alfa content", "beta" => "beta content"]);

        $expectation = [
            "title" => "Test title",
            "description" => "Test description",
            "content" => [
                "alfa" => "alfa content",
                "beta" => "beta content"
            ]
        ];

        $this->assertEquals($expectation, $output->getArrayForJson());
    }
}