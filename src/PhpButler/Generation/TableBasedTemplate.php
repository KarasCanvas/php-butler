<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Generation;

use PhpButler\Schema\TableSchema;

abstract class TableBasedTemplate extends TemplateBase
{
    /**
     * @var TableSchema table
     */
    protected $table;
    protected $namespace;
    protected $prefixLength;
    protected $modelSuffix;

    public function setTable(TableSchema $table)
    {
        if($table instanceof TableSchema) {
            $this->table = $table;
        }
    }

    public function setNamespace($value)
    {
        $this->namespace = strval($value);
    }

    public function setPrefixLength($value)
    {
        $this->prefixLength = intval($value);
    }

    public function setModelSuffix($value)
    {
        $this->modelSuffix = strval($value);
    }

    public function getModelName()
    {
        if(!$this->table) {
            return false;
        }
        $name = $this->table->getCamelizedName();
        if ($this->prefixLength) {
            $name = substr($name, $this->prefixLength);
        }
        if ($this->modelSuffix) {
            $name = $name . $this->modelSuffix;
        }
        return $name;
    }
}