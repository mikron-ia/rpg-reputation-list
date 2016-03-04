<?php

use Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException;
use Mikron\ReputationList\Domain\Service\CalculatorSeventhSeaForGroup;

/**
 * Class CalculatorSeventhSeaForGroupForGroupTest
 *
 * This tests assume CalculatorGeneric operates correctly
 */
class CalculatorSeventhSeaForGroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSeaForGroup $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws MissingCalculationBaseException
     */
    public function calculateDiceWorks($calculator, $values, $expectations)
    {
        $expectation = [
            'dice' => $expectations['dice']
        ];

        $basics = $calculator->calculateBasic($values);
        $currentState = $basics + $calculator->calculateLowestAndHighest($values) + $calculator->calculateBalanceFromNewValues($values,
                $basics);

        $result = $calculator->calculateDice($currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSeaForGroup $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws MissingCalculationBaseException
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
     * @param CalculatorSeventhSeaForGroup $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws MissingCalculationBaseException
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
     * @param CalculatorSeventhSeaForGroup $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws MissingCalculationBaseException
     */
    public function calculateDiceFailsWithoutExtremes($calculator, $values, $expectations)
    {
        $this->setExpectedException(MissingCalculationBaseException::class);
        $calculator->calculateDice([]);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSeaForGroup $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws MissingCalculationBaseException
     */
    public function calculateRecognitionValueFailsWithoutExtremes($calculator, $values, $expectations)
    {
        $this->setExpectedException(MissingCalculationBaseException::class);
        $calculator->calculateRecognitionValue([]);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param CalculatorSeventhSeaForGroup $calculator
     * @param int[] $values
     * @param int[] $expectations
     * @throws MissingCalculationBaseException
     */
    public function calculateRecognitionDiceFailsWithoutExtremes($calculator, $values, $expectations)
    {
        $this->setExpectedException(MissingCalculationBaseException::class);
        $calculator->calculateRecognitionDice([]);
    }

    /**
     * @test
     */
    public function valuesAreAdjustedProperly()
    {
        $calculator = new CalculatorSeventhSeaForGroup();

        $values = [1, 3, 5];
        $influences = [2, 3, 0];
        $expectation = [1, 3, 5, 2, 3, 0];

        $this->assertEquals($expectation, $calculator->adjustValuesWithInfluences($values, $influences));
    }


    /**
     * @test
     * @dataProvider balanceValuesProvider
     * @param int[] $values
     * @param int[] $expectation
     */
    public function balanceIsCalculatedProperly($values, $expectation)
    {
        $calculator = new CalculatorSeventhSeaForGroup();
        $this->assertEquals(
            $expectation,
            $calculator->calculateBalanceFromNewValues($values, $calculator->calculateBasic($values))
        );
    }

    public function valuesProviderForSimpleCalculations()
    {
        $calculator = new CalculatorSeventhSeaForGroup();
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
        $calculator = new CalculatorSeventhSeaForGroup();
        return [
            [
                $calculator,
                0,
                ['influence.weight' => 2],
                ['influence' => 0, 'weight' => 2]
            ],
            [
                $calculator,
                0,
                [],
                ['influence' => 0, 'weight' => 1]
            ],
            [
                $calculator,
                80,
                ['influence.weight' => 1],
                ['influence' => 80, 'weight' => 1]
            ],
            [
                $calculator,
                -80,
                ['influence.weight' => 1],
                ['influence' => -80, 'weight' => 1]
            ],
            [
                $calculator,
                80,
                ['influence.weight' => 2],
                ['influence' => 160, 'weight' => 2]
            ],
            [
                $calculator,
                80,
                ['influence.weight' => -2],
                ['influence' => -160, 'weight' => -2]
            ],
            [
                $calculator,
                -80,
                ['influence.weight' => -2],
                ['influence' => 160, 'weight' => -2]
            ],
        ];
    }

    public function balanceValuesProvider()
    {
        return [
            [[0], ['balance' => 0, 'influence' => 0]],
            [[0, 0], ['balance' => 0, 'influence' => 0]],
            [[-5, 5], ['balance' => 0, 'influence' => 0]],
            [[5, -5], ['balance' => 0, 'influence' => 0]],
            [[-10, 5], ['balance' => -5, 'influence' => 0]],
            [[5, -10], ['balance' => -5, 'influence' => 0]],
            [[-10, 5], ['balance' => -5, 'influence' => 0]]
        ];
    }
}
