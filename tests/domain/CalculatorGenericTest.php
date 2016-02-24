<?php

use Mikron\ReputationList\Domain\Service\CalculatorGeneric;

class CalculatorGenericTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider valuesProvider
     *
     * @param CalculatorGeneric $calculator
     * @param int[] $values
     * @param int[] $currentState
     * @param int[] $basics
     * @param int $lowest
     * @param int $highest
     * @param int $abs
     */
    public function calculateLowestAndHighestCalculatesCorrectly(
        $calculator,
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
        $result = $calculator->calculateLowestAndHighest($values);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProvider
     *
     * @param CalculatorGeneric $calculator
     * @param int[] $values
     * @param int[] $currentState
     * @param int[] $basics
     * @param int $lowest
     * @param int $highest
     * @param int $abs
     */
    public function calculateBasicCalculatesCorrectly($calculator, $values, $currentState, $basics, $lowest, $highest, $abs)
    {
        $expectation = $basics;
        $result = $calculator->calculateBasic($values);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProvider
     *
     * @param CalculatorGeneric $calculator
     * @param int[] $values
     * @param int[] $currentState
     * @param int[] $basics
     * @param int $lowest
     * @param int $highest
     * @param int $abs
     * @internal param int $negative
     * @internal param int $positive
     */
    public function calculateAbsoluteCalculatesCorrectly($calculator, $values, $currentState, $basics, $lowest, $highest, $abs)
    {
        $expectation = [
            'absolute' => $abs
        ];
        $result = $calculator->calculateAbsolute($values);

        $this->assertEquals($expectation, $result);
    }

    public function valuesProvider()
    {
        $calculator = new CalculatorGeneric();

        return [
            [
                $calculator,
                [0],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                0,
                0,
                0
            ],
            [
                $calculator,
                [0, 0],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                0,
                0,
                0
            ],
            [
                $calculator,
                [-5, 5],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => 0, 'negative' => -5, 'positive' => 5],
                -5,
                0,
                10
            ],
            [
                $calculator,
                [5, -5],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => 0, 'negative' => -5, 'positive' => 5],
                0,
                5,
                10
            ],
            [
                $calculator,
                [-10, 5],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => -5, 'negative' => -10, 'positive' => 5],
                -10,
                0,
                15
            ],
            [
                $calculator,
                [5, -10],
                ['balance' => 0, 'negative' => 0, 'positive' => 0],
                ['balance' => -5, 'negative' => -10, 'positive' => 5],
                -5,
                5,
                15
            ],
            [
                $calculator,
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
