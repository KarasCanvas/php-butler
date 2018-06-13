<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Phalcon;

use Phalcon\Db\AdapterInterface;
use PhpButler\Schema\SchemaProvider;
use PhpButler\Schema\TableSchema;

class MySqlSchemaProvider extends SchemaProvider
{
    /**
     * @var AdapterInterface $db
     */
    protected $db;
    protected $dbname;

    public function __construct(AdapterInterface $db, $dbname)
    {
        $this->db = $db;
        $this->dbname = $dbname;
    }

    /**
     * getTables
     * @return TableSchema[]
     */
    public function getTables()
    {
        $tables = $this->db->query('show tables;')->fetchAll();
        array_walk($tables, function (&$value) {
            $value = $value[0];
        });
        $list = [];
        foreach ($tables as $table) {
            $list[] = new TableSchema($table, $this->getColumns($table));
        }
        return $list;
    }

    public function getColumns($table)
    {
        $columns = $this->db->query("select * from information_schema.columns where TABLE_SCHEMA = '{$this->dbname}' and TABLE_NAME = '{$table}';")->fetchAll();
        array_walk($columns, function (&$value) {
            for ($i = 0; $i < 20; $i++) {
                unset($value[$i]);
            }
        });
        array_walk($columns, function(&$value) {
            $value = new MySqlColumnSchema($value);
        });
        return $columns;
    }
}