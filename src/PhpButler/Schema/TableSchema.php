<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Schema;

use PhpButler\Common\StringUtils;

class TableSchema implements \JsonSerializable
{
    protected $name;
    protected $columns;

    /**
     * TableSchema constructor.
     * @param string $name
     * @param ColumnSchema[] $columns
     */
    public function __construct($name, array $columns)
    {
        $this->name = $name;
        $this->columns = $columns;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getCamelizedName()
    {
        return StringUtils::camelize($this->getName());
    }

    public function jsonSerialize()
    {
        return [
            'name'    => $this->name,
            'columns' => $this->columns,
        ];
    }
}