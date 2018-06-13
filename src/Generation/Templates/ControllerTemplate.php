<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Generation\Templates;

use PhpButler\Generation\TableBasedTemplate;

class ControllerTemplate extends TableBasedTemplate
{
    protected $imports = ['Phalcon\Mvc\Controller'];
    protected $baseClass = 'Controller';

    public function setImports(array $imports)
    {
        $this->imports = (array)$imports;
    }

    public function setBaseClass($value)
    {
        $this->baseClass = strval($value);
    }

    public function getOutputFile()
    {
        $name = $this->getModelName() . 'Controller';
        return "{$this->outputPath}/$name.php";
    }

    public function renderContent()
    {
        ob_start();
        require 'Content/Controller.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}