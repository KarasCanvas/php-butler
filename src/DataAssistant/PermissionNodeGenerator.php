<?php
/**
 * @author Raven <karascanvas@qq.com>
 */

namespace PhpButler\DataAssistant;

use Phalcon\Annotations\Adapter as AnnotationAdapter;
use Phalcon\Annotations\Reflection;
use Phalcon\Db\AdapterInterface as DbAdapter;
use Phalcon\Di;

class PermissionNodeGenerator
{
    const NS_SEPARATOR = '\\';

    protected $path;
    protected $namespace;
    protected $db;
    protected $annotations;
    protected $nodeTable = 'admin_node';
    protected $groupTable = 'admin_group';

    public function __construct($path, $namespace, DbAdapter $db = null, AnnotationAdapter $annotations = null)
    {
        $this->path = $path;
        $this->namespace = $namespace;
        $this->db = $db == null ? Di::getDefault()->get('db') : $db;
        $this->annotations = $annotations == null ? Di::getDefault()->get('annotations') : $annotations;
    }

    public function setNodeTable($value)
    {
        $this->nodeTable = $value;
    }

    public function setGroupTale($value)
    {
        $this->groupTable = $value;
    }

    /**
     * generate
     * @param array $rootTitles [name => title]
     * @return array
     */
    public function generate(array $rootTitles = [])
    {
        $controllerNames = static::getControllerNames($this->path);
        $data = [];
        foreach ($controllerNames as $controllerName) {
            $classData = $this->getControllerData($controllerName);
            if ($classData) {
                $data[$classData['parent']][] = $classData;
            }
        }
        $idMap = [];
        $this->db->execute('truncate table `' . $this->nodeTable . '`;');
        $this->insertRootModules($data, $idMap, $rootTitles);
        $this->insertModules($data, $idMap);
        $this->insertActions($data, $idMap);
        return $idMap;
    }

    public function generatePermissions($groupId)
    {
        $controllerNames = static::getControllerNames($this->path);
        $data = [];
        foreach ($controllerNames as $controllerName) {
            $classData = static::getControllerData($controllerName);
            if ($classData) {
                $actions = array_keys($classData['actions']);
                array_walk($actions, function (&$value) {
                    $value = strtolower($value);
                });
                $data[strtolower($classData['name'])] = $actions;
            }
        }
        $this->db->update($this->groupTable, ['permissions'], [json_encode($data)], [
            'conditions' => "id = ?",
            'bind'       => [$groupId],
        ]);
        return $data;
    }

    protected function insertActions($data, &$idMap)
    {
        foreach ($data as $key => $modules) {
            foreach ($modules as $module) {
                if (!isset($idMap[$module['name']]) || !isset($module['actions'])) {
                    continue;
                }
                $pid = $idMap[$module['name']];
                foreach ($module['actions'] as $name => $action) {
                    $action['type'] = 1;
                    $action['parent_id'] = $pid;
                    $action['ordinal'] = $action['order'];
                    unset($action['order']);
                    $this->db->insert($this->nodeTable, array_values($action), array_keys($action));
                    $idMap[$module['name'] . '.' . $name] = $this->db->lastInsertId();
                }
            }
        }
    }

    protected function insertModules($data, &$idMap)
    {
        foreach ($data as $key => $modules) {
            foreach ($modules as $module) {
                if (!isset($idMap[$module['parent']])) {
                    continue;
                }
                $module['parent_id'] = $idMap[$module['parent']];
                $module['ordinal'] = $module['order'];
                unset($module['parent']);
                unset($module['order']);
                unset($module['actions']);
                $result = $this->db->insert($this->nodeTable, array_values($module), array_keys($module));
                if ($result) {
                    $idMap[$module['name']] = $this->db->lastInsertId();
                }
            }
        }
    }

    protected function insertRootModules($data, &$idMap, array $rootTitles = [])
    {
        foreach (array_keys($data) as $name) {
            $item = [
                'name'    => $name,
                'title'   => isset($rootTitles[$name]) ? $rootTitles[$name] : $name,
                'display' => intval($name != '#'),
                'icon'    => 'icon-folder',
            ];
            $result = $this->db->insert($this->nodeTable, array_values($item), array_keys($item));
            if ($result) {
                $idMap[$name] = $this->db->lastInsertId();
            }
        }
    }

    protected function getControllerData($controllerName)
    {
        $className = $this->namespace . static::NS_SEPARATOR . $controllerName . 'Controller';
        $reflection = $this->annotations->get($className);
        return static::getClassData($reflection, $controllerName);
    }

    protected static function getClassData(Reflection $reflection, $name)
    {
        $classAnnotations = $reflection->getClassAnnotations();
        if (!$classAnnotations || !$classAnnotations->has('Module')) {
            return null;
        }
        $moduleAnnotation = $classAnnotations->get('Module');
        $moduleArgs = $moduleAnnotation->getArguments();
        $moduleData = [
            'name'    => $name,
            'parent'  => '$',
            'icon'    => 'icon-app',
            'title'   => $name,
            'display' => 1,
            'order'   => 0
        ];
        foreach ($moduleData as $key => $value) {
            if (isset($moduleArgs[$key])) {
                $moduleData[$key] = $moduleArgs[$key];
            }
        }
        $moduleData['actions'] = static::getMethodsData($reflection);
        return $moduleData;
    }

    protected static function getMethodsData(Reflection $reflection)
    {
        $list = [];
        $methodsAnnotations = $reflection->getMethodsAnnotations();
        if ($methodsAnnotations) {
            foreach ($methodsAnnotations as $methodName => $methodAnnotations) {
                if (!$methodAnnotations->has('Action')) {
                    continue;
                }
                $methodAnnotation = $methodAnnotations->get('Action');
                $args = $methodAnnotation->getArguments();
                $actionName = preg_replace('/Action$/', '', $methodName);
                $action = [
                    'name'    => $actionName,
                    'icon'    => 'icon-app',
                    'title'   => $actionName,
                    'display' => 1,
                    'order'   => 0
                ];
                foreach ($action as $key => $value) {
                    if (isset($args[$key])) {
                        $action[$key] = $args[$key];
                    }
                }
                $list[$action['name']] = $action;
            }
        }
        return $list;
    }

    protected static function getControllerNames($path)
    {
        $list = [];
        foreach (scandir($path) as $file) {
            if ($file != '.' && $file != '..' && preg_match('/^(\w+)Controller\.php$/i', $file, $matches)) {
                $list[] = $matches[1];
            }
        }
        return $list;
    }
}