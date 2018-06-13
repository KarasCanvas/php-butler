<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Generation;

abstract class TemplateBase implements TemplateInterface
{
    protected $outputPath;

    abstract function getOutputFile();

    public function setOutputPath($path)
    {
        $this->outputPath = strval($path);
    }

    abstract function renderContent();

    public function render()
    {
        $filename = $this->getOutputFile();
        if (file_exists($filename)) {
            return false;
        } else {
            $dir = dirname($filename);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($filename, $this->renderContent());
            return true;
        }
    }
}