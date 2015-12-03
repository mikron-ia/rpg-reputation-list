<?php

namespace Mikron\ReputationList\Domain\Blueprint;

interface AuthenticationToken
{
    /**
     * Verifies if the other token is compatible with $this
     *
     * @param AuthenticationToken $token
     * @return boolean true is token checks out
     */
    public function checksOut($token);
}
