<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Schema;

abstract class ColumnSchema implements \JsonSerializable
{
    public abstract function getName();

    public abstract function getType();

    public abstract function getSize();

    public abstract function getPrecision();

    public abstract function getScale();

    public abstract function isNullable();

    public abstract function isUnique();

    public abstract function isPrimaryKey();

    public abstract function isForeignKey();

    public abstract function getNativeType();

    public abstract function getComment();

    public abstract function getProperty($name);

    public function jsonSerialize()
    {
        return [
            'name'        => $this->getName(),
            'type'        => $this->getType(),
            'size'        => $this->getSize(),
            'precision'   => $this->getPrecision(),
            'scale'       => $this->getScale(),
            'nullable'    => $this->isNullable(),
            'unique'      => $this->isUnique(),
            'primary_key' => $this->isPrimaryKey(),
            'foreign_key' => $this->isForeignKey(),
            'native_type' => $this->getNativeType(),
            'comment'     => $this->getComment(),
        ];
    }
}