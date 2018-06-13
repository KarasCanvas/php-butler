<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\Generation;

interface TemplateInterface
{
    /**
     * @return string|null
     */
    function getOutputFile();

    /**
     * @param string $path
     * @return void
     */
    function setOutputPath($path);

    /**
     * @return string|null
     */
    function renderContent();

    /**
     * @return boolean
     */
    function render();
}