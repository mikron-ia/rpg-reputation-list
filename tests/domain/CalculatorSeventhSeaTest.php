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
     * @param int[] $expectation
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateInfluenceWorks($calculator, $balance, $parameters, $expectation)
    {
        $currentStateBase = $calculator->calculateBasic([$balance]);

        $currentState = $currentStateBase + $parameters;

        $result = $calculator->calculateInfluenceExtended($currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     */
    public function calculateInfluenceReturnsDefaultWeightWithoutParameters()
    {
        $calculator = new CalculatorSeventhSea();
        $currentStateBase = $calculator->calculateBasic([0]);
        $result = $calculator->calculateInfluenceExtended($currentStateBase);

        $this->assertEquals(1, $result['weight']);
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
}
