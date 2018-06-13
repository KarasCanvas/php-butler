<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\MvcGeneration;

class ActionDescriptor
{
    const RESPONSE_XML = 'xml';
    const RESPONSE_JSON = 'json';
    const RESPONSE_TEXT = 'text';
    const RESPONSE_HTML = 'html';
    const RESPONSE_VIEW = 'view';
    const RESPONSE_AJAX = 'ajax';
    const RESPONSE_REDIRECT = 'redirect';

    /**
     * @var ControllerDescriptor $controller
     */
    protected $controller;
    protected $name = 'index';
    protected $methods = ['GET'];
    protected $responseType = 'view';
    protected $comment = null;

    public function __construct($name, ControllerDescriptor $controller = null)
    {
        $this->name = $name;
        if ($controller) {
            $this->controller = $controller;
            $controller->addAction($this);
        }
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController(ControllerDescriptor $value)
    {
        if (!$value instanceof ControllerDescriptor) {
            return;
        }
        $this->controller = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = strval($value);
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setMethods($value)
    {
        $this->methods = (array)$value;
    }

    public function getResponseType()
    {
        return $this->responseType;
    }

    public function setResponseType($value)
    {
        $this->responseType = $this->normalizeResponseType($value);
    }

    protected function normalizeResponseType($value)
    {
        return strtolower($value);
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($value)
    {
        $this->comment = strval($value);
    }
}