<?php

/**
 * Data file for 7th Sea RPG system in its first edition, a creation of John Wick, originally published by
 * Alderac Entertainment Group
 *
 * http://www.alderac.com/7thsea/
 * No copyright infringement intended, no copyrighted content used
 */
return [
    'systemLabel' => 'SeventhSea',
    'systemData' => [
        /**
         * Due to copyrighted and highly limited nature of existing data, no reputations are listed here
         * Should you have created your own system for managing reputation in 7th Sea system, please place it in the `epic.php` config file
         */
        'reputations' => []
    ],
    'calculator' => [
        'person' => 'Mikron\ReputationList\Domain\Service\CalculatorSeventhSea',
        'group' => 'Mikron\ReputationList\Domain\Service\CalculatorSeventhSeaForGroup'
    ]
];
