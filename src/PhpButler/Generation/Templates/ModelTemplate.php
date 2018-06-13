<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Generation\Templates;

use PhpButler\Generation\TableBasedTemplate;

class ModelTemplate extends TableBasedTemplate
{
    public $generateGetSource = true;
    public $generateAuthor = true;

    public function getOutputFile()
    {
        $name = $this->getModelName();
        return "{$this->outputPath}/$name.php";
    }

    public function renderContent()
    {
        ob_start();
        require 'Content/Model.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}