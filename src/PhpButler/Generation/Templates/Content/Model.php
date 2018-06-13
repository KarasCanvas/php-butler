<?php
/**
 * @author Raven <karascanvas@qq.com>
 * @var \PhpButler\Generation\Templates\ModelTemplate $this
 */
$name = $this->getModelName();
?>
<?='<?php'?>
<? if($this->generateAuthor) { ?>

/**
* @author Raven<karascanvas@qq.com>
*/
<? } ?>
namespace <?=$this->namespace?>;

use Phalcon\Mvc\Model;

/**
<?
foreach ($this->table->getColumns() as $column) {
?>
 * @property <?=$column->getType()?> $<?=$column->getName()?> [<?=$column->getProperty('COLUMN_TYPE')?>] <?=$column->getComment()?>

<? } ?>
 */
class <?=$name?> extends Model
{
    public function initialize()
    {
        $this->allowEmptyStringValues([ ]);
    }

<? if($this->generateGetSource) { ?>
    public function getSource()
    {
        return '<?=$this->table->getName()?>';
    }

<? } ?>
    public function validation()
    {
        return !$this->validationHasFailed();
    }

    public function beforeCreate()
    {
    }

    public function beforeSave()
    {
    }
}
