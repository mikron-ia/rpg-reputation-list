<?php

namespace Mikron\ReputationList\Domain\Blueprint;

/**
 * Interface Storage - responsible for blueprinting storage implementations
 * @package Mikron\ReputationList\Domain\Blueprint
 */
interface StorageEngine
{
    public function selectAll($table, $primaryKeyName);

    public function selectByKey($table, $primaryKeyName, $keyName, $keyValues);

    public function selectByPrimaryKey($table, $primaryKeyName, $primaryKeyValues);

    public function selectViaAssociation($tableToSelectFrom, $associationTable, $primaryKeyName, $keyName, $keyValues);

    public function countViaAssociation($tableToSelectFrom, $associationTable, $primaryKeyName, $keyName, $keyValues);
}
