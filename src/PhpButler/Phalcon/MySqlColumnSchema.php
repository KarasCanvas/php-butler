<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Phalcon;

use PhpButler\Schema\ColumnSchema;

class MySqlColumnSchema extends ColumnSchema
{
    /**
     * @var array $info
     */
    protected $info;

    public function __construct(array $info)
    {
        $this->info = $info;
    }

    public function getName()
    {
        return $this->info['COLUMN_NAME'];
    }

    public function getType()
    {
        return $this->getPhpDataType($this->info['DATA_TYPE']);
    }

    public function getSize()
    {
        return $this->info['CHARACTER_MAXIMUM_LENGTH'];
    }

    public function getPrecision()
    {
        return $this->info['NUMERIC_PRECISION'];
    }

    public function getScale()
    {
        return $this->info['NUMERIC_SCALE'];
    }

    public function isNullable()
    {
        return $this->info['IS_NULLABLE'] == 'YES';
    }

    public function isUnique()
    {
        return strpos($this->info['COLUMN_KEY'], 'UNI') !== false;
    }

    public function isPrimaryKey()
    {
        return strpos($this->info['COLUMN_KEY'], 'PRI') !== false;
    }

    public function isForeignKey()
    {
        return strpos($this->info['COLUMN_KEY'], 'FOR') !== false;
    }

    public function getNativeType()
    {
        return $this->info['DATA_TYPE'];
    }

    public function getComment()
    {
        return $this->info['COLUMN_COMMENT'];
    }

    public function getProperty($name)
    {
        return $this->info[$name];
    }

    protected function getPhpDataType($type)
    {
        $type = strtolower($type);
        if(strpos($type, 'char') !== false || strpos($type, 'text') !== false)
        {
            return 'string';
        }
        if(strpos($type, 'int') !== false || strpos($type, 'byte') !== false)
        {
            return 'int';
        }
        if(strpos($type, 'numeric') !== false || strpos($type, 'decimal') !== false)
        {
            return 'double';
        }
        return 'mixed';
    }
}