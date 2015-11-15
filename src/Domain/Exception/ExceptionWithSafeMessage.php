<?php

namespace Mikron\ReputationList\Domain\Exception;

/**
 * Class ExceptionWithSafeMessage
 * Contains message safe for display in a non-debug environment
 *
 * @package Mikron\ReputationList\Domain\Exception
 */
class ExceptionWithSafeMessage extends \Exception
{
    protected $safeMessage;

    public function __construct($safeMessage = "", $message = "", $code = 0, Exception $previous = null)
    {
        $this->safeMessage = $safeMessage;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getSafeMessage()
    {
        return $this->safeMessage;
    }
}
