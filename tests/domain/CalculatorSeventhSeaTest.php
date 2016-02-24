<?php

use Mikron\ReputationList\Domain\Service\CalculatorGeneric;
use Mikron\ReputationList\Domain\Service\CalculatorSeventhSea;

/**
 * Class CalculatorSeventhSeaTest
 *
 * This tests assume CalculatorGeneric operates correctly
 */
class CalculatorSeventhSeaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSea $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateDiceWorks($calculator, $values, $expectations)
    {
        $expectation = [
            'dice' => $expectations['dice']
        ];

        $currentState = $calculator->calculateBasic($values) + $calculator->calculateLowestAndHighest($values);

        $result = $calculator->calculateDice($currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSea $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateRecognitionWorks($calculator, $values, $expectations)
    {
        $expectation = [
            'recognition' => $expectations['recognition']
        ];

        $currentState = $calculator->calculateBasic($values) + $calculator->calculateAbsolute($values);

        $result = $calculator->calculateRecognitionValue($currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSea $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateRecognitionDiceWorks($calculator, $values, $expectations)
    {
        $expectation = [
            'recognitionDice' => $expectations['recognitionDice']
        ];

        $currentStateBase = $calculator->calculateBasic($values) + $calculator->calculateAbsolute($values);

        $currentState = $calculator->calculateRecognitionValue($currentStateBase);

        $result = $calculator->calculateRecognitionDice($currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSea $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateDiceFailsWithoutExtremes($calculator, $values, $expectations)
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        $calculator->calculateDice([]);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSea $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateRecognitionValueFailsWithoutExtremes($calculator, $values, $expectations)
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        $calculator->calculateRecognitionValue([]);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSea $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateRecognitionDiceFailsWithoutExtremes($calculator, $values, $expectations)
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        $calculator->calculateRecognitionDice([]);
    }

    /**
     * @test
     * @dataProvider valuesProviderForInfluenceCalculations
     *
     * @param CalculatorSeventhSea $calculator
     * @param int $balance
     * @param int[] $parameters
     * @param int $expectedInfluence
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateInfluenceWorks($calculator, $balance, $parameters, $expectedInfluence)
    {
        $expectation = [
            'influence' => $expectedInfluence
        ];

        $currentStateBase = $calculator->calculateBasic([$balance]);

        $currentState = $currentStateBase + $parameters;

        $result = $calculator->calculateInfluenceExtended($currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     */
    public function calculateInfluenceWorksReturnsEmptyWithoutParameters()
    {
        $calculator = new CalculatorSeventhSea();
        $currentStateBase = $calculator->calculateBasic([0]);
        $result = $calculator->calculateInfluenceExtended($currentStateBase);

        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function calculateInfluenceWorksFailsWithZeroDivider()
    {
        $calculator = new CalculatorSeventhSea();
        $currentStateBase = $calculator->calculateBasic([0]);
        $currentState = $currentStateBase + ['influenceMultiplier' => 1, 'influenceDivider' => 0];
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        $calculator->calculateInfluenceExtended($currentState);
    }

    public function valuesProviderForSimpleCalculations()
    {
        $calculator = new CalculatorSeventhSea();
        return [
            [
                $calculator,
                [0],
                ['dice' => 0, 'recognition' => 0, 'recognitionDice' => 0]
            ],
            [
                $calculator,
                [4],
                ['dice' => 0, 'recognition' => 4, 'recognitionDice' => 0]
            ],
            [
                $calculator,
                [-4],
                ['dice' => 0, 'recognition' => 4, 'recognitionDice' => 0]
            ],
            [
                $calculator,
                [5],
                ['dice' => 1, 'recognition' => 5, 'recognitionDice' => 1]
            ],
            [
                $calculator,
                [-5],
                ['dice' => -1, 'recognition' => 5, 'recognitionDice' => 1]
            ],
            [
                $calculator,
                [0, 0],
                ['dice' => 0, 'recognition' => 0, 'recognitionDice' => 0]
            ],
            [
                $calculator,
                [-5, 5],
                ['dice' => 0, 'recognition' => 10, 'recognitionDice' => 1]
            ],
            [
                $calculator,
                [5, -5],
                ['dice' => 0, 'recognition' => 10, 'recognitionDice' => 1]
            ],
            [
                $calculator,
                [-10, 5],
                ['dice' => 0, 'recognition' => 15, 'recognitionDice' => 2]
            ],
            [
                $calculator,
                [-10, 5],
                ['dice' => 0, 'recognition' => 15, 'recognitionDice' => 2]
            ],
            [
                $calculator,
                [5, -10],
                ['dice' => -1, 'recognition' => 15, 'recognitionDice' => 2]
            ],
            [
                $calculator,
                [10, -5],
                ['dice' => 0, 'recognition' => 15, 'recognitionDice' => 2]
            ],
        ];
    }

    public function valuesProviderForInfluenceCalculations()
    {
        $calculator = new CalculatorSeventhSea();
        return [
            [
                $calculator,
                0,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                0
            ],
            [
                $calculator,
                80,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                20
            ],
            [
                $calculator,
                -80,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                -20
            ],
            [
                $calculator,
                80,
                ['influenceMultiplier' => 2, 'influenceDivider' => 9],
                18
            ],
            [
                $calculator,
                -80,
                ['influenceMultiplier' => 2, 'influenceDivider' => 9],
                -18
            ],
            /* Corner cases for positives */
            [
                $calculator,
                3,
                ['influenceMultiplier' => 1, 'influenceDivider' => 8],
                0
            ],
            [
                $calculator,
                4,
                ['influenceMultiplier' => 1, 'influenceDivider' => 8],
                1
            ],
            [
                $calculator,
                4,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                1
            ],
            [
                $calculator,
                6,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                2
            ],
            [
                $calculator,
                6,
                ['influenceMultiplier' => 2, 'influenceDivider' => 9],
                1
            ],
            /* Corner cases for negatives */
            [
                $calculator,
                -3,
                ['influenceMultiplier' => 1, 'influenceDivider' => 8],
                0
            ],
            [
                $calculator,
                -4,
                ['influenceMultiplier' => 1, 'influenceDivider' => 8],
                -1
            ],
            [
                $calculator,
                -4,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                -1
            ],
            [
                $calculator,
                -6,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                -2
            ],
            [
                $calculator,
                -6,
                ['influenceMultiplier' => 2, 'influenceDivider' => 9],
                -1
            ],
        ];
    }
}
