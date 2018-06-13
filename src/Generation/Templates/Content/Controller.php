<?php
/**
 * @author Raven <karascanvas@qq.com>
 * @var \PhpButler\Generation\Templates\ControllerTemplate $this
 */
$name = $this->getModelName();
?>
<?='<?php'?>

namespace <?=$this->namespace?>;

<? foreach ($this->imports as $import) { ?>
use <?=$import?>;
<? } ?>

/**
* @Code(author:'$', progress:0, version: '1.0')
* @Module(name:'<?=$name?>', parent:'$', icon:'icon-app', title:'<?=$name?>', order:0)
*/
class <?=$name?>Controller extends <?=$this->baseClass, PHP_EOL?>
{
    /**
    * @Code(author:'$', progress:0, version: '1.0')
    * @Action(name:'index', icon:'icon-act', title:'Index', order:0)
    */
    public function indexAction()
    {
        return 'Hello world!';
    }
}