<?php

namespace Mikron\ReputationList\Infrastructure\Factory;

use Mikron\ReputationList\Domain\ValueObject;

/**
 * Class AuthenticationToken
 * @package Mikron\ReputationList\Infrastructure\Factory
 */
class AuthenticationToken
{
    public function createFromString($string)
    {
        return new ValueObject\AuthenticationToken($string);
    }
}
