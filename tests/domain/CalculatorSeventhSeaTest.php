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
     * @dataProvider valuesProvider
     *
     * @param int[] $values
     * @param $expectations
     * @return int|number
     * @throws \Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage
     */
    public function calculateDiceWorks($values, $expectations)
    {
        $expectation = [
            'dice' => $expectations['dice']
        ];

        $currentState = CalculatorGeneric::calculateSimple($values, [])
            + CalculatorGeneric::calculateLowestAndHighest($values, []);

        $result = CalculatorSeventhSea::seventhSeaCalculateDice($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProvider
     *
     * @param int[] $values
     * @param $expectations
     * @return int|number
     * @throws \Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage
     */
    public function calculateRecognitionWorks($values, $expectations)
    {
        $expectation = [
            'recognition' => $expectations['recognition']
        ];

        $currentState = CalculatorGeneric::calculateSimple($values, [])
            + CalculatorGeneric::calculateAbsolute($values, []);
        $result = CalculatorSeventhSea::seventhSeaCalculateRecognitionValue($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    /**
     * @test
     * @dataProvider valuesProvider
     *
     * @param int[] $values
     * @param $expectations
     * @return int|number
     * @throws \Mikron\ReputationList\Domain\Exception\ExceptionWithSafeMessage
     */
    public function calculateRecognitionDiceWorks($values, $expectations)
    {
        $expectation = [
            'recognitionDice' => $expectations['recognitionDice']
        ];

        $currentStateBase = CalculatorGeneric::calculateSimple($values, [])
            + CalculatorGeneric::calculateAbsolute($values, []);
        $currentState = CalculatorSeventhSea::seventhSeaCalculateRecognitionValue($values, $currentStateBase);

        $result = CalculatorSeventhSea::seventhSeaCalculateRecognitionDice($values, $currentState);

        $this->assertEquals($expectation, $result);
    }

    public function valuesProvider()
    {
        return [
            [
                [0],
                ['dice' => 0, 'recognition' => 0, 'recognitionDice' => 0]
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
        ];
    }
}
