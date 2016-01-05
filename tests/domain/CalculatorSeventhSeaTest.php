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
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateDiceWorks($values, $expectations)
    {
        $expectation = [
            'dice' => $expectations['dice']
        ];

        $currentState = CalculatorGeneric::calculateBasic($values, [])
            + CalculatorGeneric::calculateLowestAndHighest($values, []);

        $result = CalculatorSeventhSea::calculateDice($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateRecognitionWorks($values, $expectations)
    {
        $expectation = [
            'recognition' => $expectations['recognition']
        ];

        $currentState = CalculatorGeneric::calculateBasic($values, [])
            + CalculatorGeneric::calculateAbsolute($values, []);
        $result = CalculatorSeventhSea::calculateRecognitionValue($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateRecognitionDiceWorks($values, $expectations)
    {
        $expectation = [
            'recognitionDice' => $expectations['recognitionDice']
        ];

        $currentStateBase = CalculatorGeneric::calculateBasic($values, [])
            + CalculatorGeneric::calculateAbsolute($values, []);
        $currentState = CalculatorSeventhSea::calculateRecognitionValue($values, $currentStateBase);

        $result = CalculatorSeventhSea::calculateRecognitionDice($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateDiceFailsWithoutExtremes($values, $expectations)
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        CalculatorSeventhSea::calculateDice($values, []);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateRecognitionValueFailsWithoutExtremes($values, $expectations)
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        CalculatorSeventhSea::calculateRecognitionValue($values, []);
    }

    /**
     * @test
     * @dataProvider valuesProviderForSimpleCalculations
     *
     * @param int[] $values
     * @param int[] $expectations
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateRecognitionDiceFailsWithoutExtremes($values, $expectations)
    {
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        CalculatorSeventhSea::calculateRecognitionDice($values, []);
    }

    /**
     * @test
     * @dataProvider valuesProviderForInfluenceCalculations
     *
     * @param int $balance
     * @param int[] $parameters
     * @param int $expectedInfluence
     * @throws \Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException
     */
    public function calculateInfluenceWorks($balance, $parameters, $expectedInfluence)
    {
        $expectation = [
            'influence' => $expectedInfluence
        ];

        $currentStateBase = CalculatorGeneric::calculateBasic([$balance], []);

        $currentState = $currentStateBase + $parameters;

        $result = CalculatorSeventhSea::calculateInfluenceExtended([$balance], $currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     */
    public function calculateInfluenceWorksFailsWithoutParameters()
    {
        $currentStateBase = CalculatorGeneric::calculateBasic([0], []);
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        CalculatorSeventhSea::calculateInfluenceExtended([0], $currentStateBase);
    }

    /**
     * @test
     */
    public function calculateInfluenceWorksFailsWithZeroDivider()
    {
        $currentStateBase = CalculatorGeneric::calculateBasic([0], []);
        $currentState = $currentStateBase + ['influenceMultiplier' => 1, 'influenceDivider' => 0];
        $this->setExpectedException('\Mikron\ReputationList\Domain\Exception\MissingCalculationBaseException');
        CalculatorSeventhSea::calculateInfluenceExtended([0], $currentState);
    }

    public function valuesProviderForSimpleCalculations()
    {
        return [
            [
                [0],
                ['dice' => 0, 'recognition' => 0, 'recognitionDice' => 0]
            ],
            [
                [4],
                ['dice' => 0, 'recognition' => 4, 'recognitionDice' => 0]
            ],
            [
                [-4],
                ['dice' => 0, 'recognition' => 4, 'recognitionDice' => 0]
            ],
            [
                [5],
                ['dice' => 1, 'recognition' => 5, 'recognitionDice' => 1]
            ],
            [
                [-5],
                ['dice' => -1, 'recognition' => 5, 'recognitionDice' => 1]
            ],
            [
                [0, 0],
                ['dice' => 0, 'recognition' => 0, 'recognitionDice' => 0]
            ],
            [
                [-5, 5],
                ['dice' => 0, 'recognition' => 10, 'recognitionDice' => 1]
            ],
            [
                [5, -5],
                ['dice' => 0, 'recognition' => 10, 'recognitionDice' => 1]
            ],
            [
                [-10, 5],
                ['dice' => 0, 'recognition' => 15, 'recognitionDice' => 2]
            ],
            [
                [-10, 5],
                ['dice' => 0, 'recognition' => 15, 'recognitionDice' => 2]
            ],
            [
                [5, -10],
                ['dice' => -1, 'recognition' => 15, 'recognitionDice' => 2]
            ],
            [
                [10, -5],
                ['dice' => 0, 'recognition' => 15, 'recognitionDice' => 2]
            ],
        ];
    }

    public function valuesProviderForInfluenceCalculations()
    {
        return [
            [
                0,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                0
            ],
            [
                80,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                20
            ],
            [
                -80,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                -20
            ],
            [
                80,
                ['influenceMultiplier' => 2, 'influenceDivider' => 9],
                18
            ],
            [
                -80,
                ['influenceMultiplier' => 2, 'influenceDivider' => 9],
                -18
            ],
            /* Corner cases for positives */
            [
                3,
                ['influenceMultiplier' => 1, 'influenceDivider' => 8],
                0
            ],
            [
                4,
                ['influenceMultiplier' => 1, 'influenceDivider' => 8],
                1
            ],
            [
                4,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                1
            ],
            [
                6,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                2
            ],
            [
                6,
                ['influenceMultiplier' => 2, 'influenceDivider' => 9],
                1
            ],
            /* Corner cases for negatives */
            [
                -3,
                ['influenceMultiplier' => 1, 'influenceDivider' => 8],
                0
            ],
            [
                -4,
                ['influenceMultiplier' => 1, 'influenceDivider' => 8],
                -1
            ],
            [
                -4,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                -1
            ],
            [
                -6,
                ['influenceMultiplier' => 2, 'influenceDivider' => 8],
                -2
            ],
            [
                -6,
                ['influenceMultiplier' => 2, 'influenceDivider' => 9],
                -1
            ],
        ];
    }
}
