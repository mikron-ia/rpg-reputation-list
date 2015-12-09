<?php

use Mikron\ReputationList\Domain\Service\CalculatorGeneric;

class CalculatorGenericTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider valuesProvider
     *
     * @param int[] $values
     * @param int[] $currentState
     * @param int[] $basics
     * @param int $lowest
     * @param int $highest
     * @param int $abs
     */
    public function calculateLowestAndHighestCalculatesCorrectly(
        $values,
        $currentState,
        $basics,
        $lowest,
        $highest,
        $abs
    ) {
        $expectation = [
            'negativeMin' => $lowest,
            'positiveMax' => $highest
        ];
        $result = CalculatorGeneric::calculateLowestAndHighest($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProvider
     *
     * @param int[] $values
     * @param int[] $currentState
     * @param int[] $basics
     * @param int $lowest
     * @param int $highest
     * @param int $abs
     */
    public function calculateBasicsCalculatesCorrectly($values, $currentState, $basics, $lowest, $highest, $abs)
    {
        $expectation = $basics;
        $result = CalculatorGeneric::calculateSimple($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProvider
     *
     * @param int[] $values
     * @param int[] $currentState
     * @param int[] $basics
     * @param int $lowest
     * @param int $highest
     * @param int $abs
     * @internal param int $negative
     * @internal param int $positive
     */
    public function calculateAbsoluteCalculatesCorrectly($values, $currentState, $basics, $lowest, $highest, $abs)
    {
        $expectation = [
            'absolute' => $abs
        ];
        $result = CalculatorGeneric::calculateAbsolute($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    public function valuesProvider()
    {
        return [
            [
                [0],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                0,
                0,
                0
            ],
            [
                [0, 0],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                0,
                0,
                0
            ],
            [
                [-5, 5],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => 0, 'negative' => -5, 'positive' => 5],
                -5,
                0,
                10
            ],
            [
                [5, -5],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => 0, 'negative' => -5, 'positive' => 5],
                0,
                5,
                10
            ],
            [
                [-10, 5],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => -5, 'negative' => -10, 'positive' => 5],
                -10,
                0,
                15
            ],
            [
                [5, -10],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => -5, 'negative' => -10, 'positive' => 5],
                -5,
                5,
                15
            ],
            [
                [-10, 5],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => -5, 'negative' => -10, 'positive' => 5],
                -10,
                0,
                15
            ],
        ];
    }
}
