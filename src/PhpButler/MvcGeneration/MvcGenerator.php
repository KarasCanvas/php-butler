<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\MvcGeneration;

use PhpButler\Common\StringUtils;

final class MvcGenerator
{
    /**
     * @param ControllerDescriptor[] $controllers $controllers
     * @param string $controllerDir
     * @param string $viewDir
     * @param string $controllerNamespace
     * @param string $controllerBaseType
     * @return array
     */
    public static function generate(array $controllers, $controllerDir, $viewDir, $controllerNamespace, $controllerBaseType = 'Phalcon\Mvc\Controller')
    {
        $result = [];
        $result['controllers'] = [];
        foreach ($controllers as $controller) {
            self::generateController($controller, $controllerDir, $controllerNamespace, $controllerBaseType, $result['controllers']);
            $result['views'] = self::generateViews($controller, $viewDir);
        }
        return $result;
    }

    protected static function generateController(ControllerDescriptor $controller, $rootPath, $namespace, $baseType, &$output)
    {
        $controllerName = StringUtils::camelize($controller->getName()) . 'Controller';
        $path = $rootPath . DIRECTORY_SEPARATOR . $controllerName . '.php';
        if (file_exists($path)) {
            $output['skipped'][] = $path;
        } else {
            $dir = dirname($path);
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
            }
            file_put_contents($path, self::getControllerCode($controller, $namespace, $baseType));
            $output['created'][] = $path;
        }
    }

    protected static function getControllerCode(ControllerDescriptor $controller, $namespace, $baseType)
    {
        $tmp = explode('\\', $baseType);
        $baseName = $tmp[count($tmp) - 1];
        $name = StringUtils::camelize($controller->getName());
        $lines = [];
        $lines[] = '<?php';
        $lines[] = 'namespace ' . $namespace . ';';
        $lines[] = '';
        $lines[] = 'use ' . $baseType . ';';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * @Code(author:\'#\', progress:0)';
        $lines[] = ' */';
        $lines[] = 'class ' . $name . 'Controller extends ' . $baseName;
        $lines[] = '{';
        foreach ($controller->getActions() as $action) {
            $lines[] = self::getActionCode($action);
            $lines[] = '';
        }
        $lines[] = '}';
        $lines[] = '';
        return implode(PHP_EOL, $lines);
    }

    protected static function getActionCode(ActionDescriptor $action)
    {
        $name = $action->getName();
        if (strpos($name, '-') > 0) {
            $name = str_replace('-', '_', $name);
        }
        $comment = $action->getComment() == null ? $name : $action->getComment();
        $lines = [];
        $lines[] = '    /**';
        $lines[] = '    * ' . $comment;
        $lines[] = '    * @AllowAnonymous';
        $lines[] = '    * @Code(author:\'#\', progress:0)';
        $lines[] = '    */';
        $lines[] = '    public function ' . $name . 'Action()';
        $lines[] = '    {';
        $lines[] = '        // TODO: impl ' . StringUtils::camelize($action->getController()->getName()) . 'Controller::' . $name;
        switch ($action->getResponseType()) {
            case ActionDescriptor::RESPONSE_JSON:
            case ActionDescriptor::RESPONSE_AJAX:
                $lines[] = '        return json_encode([\'success\' => true]);';
                break;
            case ActionDescriptor::RESPONSE_TEXT:
                $lines[] = '        return \'success\';';
                break;
            case ActionDescriptor::RESPONSE_REDIRECT:
                $lines[] = '        return $this->response->redirect(\'\', false);';
                break;
            default:
                break;
        }
        $lines[] = '    }';
        return implode(PHP_EOL, $lines);
    }

    protected static function generateViews(ControllerDescriptor $controller, $rootPath)
    {
        $result = [];
        foreach ($controller->getActions() as $action) {
            if ($action->getResponseType() != ActionDescriptor::RESPONSE_VIEW) {
                continue;
            }
            $controllerName = StringUtils::camelize($controller->getName());
            $actioName = str_replace('-', '_', $action->getName());
            $path = $rootPath . DIRECTORY_SEPARATOR . $controllerName . DIRECTORY_SEPARATOR . $actioName . '.phtml';
            if (file_exists($path)) {
                $result['skipped'][] = $path;
            } else {
                $dir = dirname($path);
                if (!file_exists($dir)) {
                    @mkdir($dir, 0777, true);
                }
                file_put_contents($path, '<h1>TODO: impl View:' . $controllerName . '.' . $actioName . '</h1>');
                $result['created'][] = $path;
            }
        }
        return $result;
    }
}