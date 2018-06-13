<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\MvcGeneration;

final class DataParser
{
    /**
     * @param array $list
     * @return ControllerDescriptor[]
     */
    public static function parse(array $list)
    {
        $data = [];
        foreach ($list as $item) {
            $data[$item['controller']][] = $item;
        }
        $result = [];
        foreach ($data as $name => $item) {
            $controller = new ControllerDescriptor($name);
            foreach ($item as $subitem) {
                $action = new ActionDescriptor($subitem['action'], $controller);
                $action->setMethods($subitem['method']);
                $action->setResponseType($subitem['response']);
                $action->setComment($subitem['comment']);
            }
            $result[] = $controller;
        }
        return $result;
    }
}