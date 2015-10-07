<?php

namespace Mikron\ReputationList\Domain\Blueprint;

/**
 * Interface Storage - responsible for blueprinting storage implementations
 * @package Mikron\ReputationList\Domain\Blueprint
 */
interface Storage
{
    public function selectAll($table, $primaryKeyName);

    public function selectByPrimaryKey($table, $primaryKeyName, $primaryKeyValues);
}
