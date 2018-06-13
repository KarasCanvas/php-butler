<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\MvcGeneration;

class ControllerDescriptor
{
    protected $name = 'home';
    /**
     * @var ActionDescriptor[] actions
     */
    protected $actions = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = strval($value);
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function setActions(array $value)
    {
        $this->actions = (array)$value;
    }

    public function addAction(ActionDescriptor $value)
    {
        if (!$value instanceof ActionDescriptor) {
            return;
        }
        $this->actions[] = $value;
    }
}