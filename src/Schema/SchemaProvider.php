<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Schema;

abstract class SchemaProvider
{
    /**
     * getTables
     * @return TableSchema[]
     */
    abstract function getTables();
}