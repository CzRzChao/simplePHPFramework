<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:23
 * Desc:
 */

namespace Framework;

class Router extends Injectable
{

    protected $controller;
    protected $action;
    protected $controller_suffix = '';
    protected $action_suffix     = '';

    public function __construct($config = null)
    {
        $this->controller_suffix = isset($config['controller_suffix']) ? $config['controller_suffix'] : 'Controller';
        $this->action_suffix     = isset($config['action_suffix']) ? $config['action_suffix'] : 'Action';
    }

    public function handle($controller, $action)
    {
        $this->controller = $controller;
        $this->action = $action;
    }

    public function match()
    {
        $dispatcher      = $this->dispatcher;
        $default_handle  = $dispatcher->getDefaultHandle();
        $controller_name = '';
        $action_name     = '';
        if (empty($this->uri)) {
            return false;
        }
        $uri_array = array_values(array_filter(explode('/', $this->uri)));
        $num       = count($uri_array);
        switch ($num) {
            case 0:
                $controller_name = $default_handle['controller'];
                $action_name     = $default_handle['action'];
                break;
            case 1;
                $controller_name = ucfirst($uri_array[0]);
                $action_name     = $default_handle['action'];
                break;
            case 2:
                $controller_name = ucfirst($uri_array[0]);
                $action_name     = lcfirst($uri_array[1]);
                break;
            default:
                break;
        }

        $controller = $controller_name . $this->controller_suffix;
        $action     = $action_name . $this->action_suffix;
        $class_name = '';
        $found      = false;
        // 默认命名空间
        if (!empty($default_handle['default_namespace'])) {
            $default_namespace = implode('\\', $default_handle['default_namespace']);
            $class_name        = $default_namespace . '\\' . $controller;
            if (class_exists($class_name)) {
                if (method_exists($class_name, $action)) {
                    $found = true;
                }
            }
        }

        // 不使用命名空间
        if (!$found && class_exists($controller)) {
            $class_name = $controller;
            if (method_exists($class_name, $action)) {
                $found = true;
            }
        }

        // 从其他命名空间进行查找
        foreach ($default_handle['namespaces'] as $namespace) {
            if ($found) {
                break;
            }
            if (substr($namespace, -1) === '\\') {
                $class_name = $namespace . $this->default_namespace . '\\' . $controller;
            } else {
                $class_name = $namespace . '\\' . $this->default_namespace . '\\' . $controller;
            }
            if (class_exists($class_name)) {
                if (method_exists($class_name, $action)) {
                    $found = true;
                }
            }
        }
        if ($found) {
            return ['class_name' => $class_name, 'action' => $action];
        }
        throw new Exception(Exception::ROUTER_FAILED);
    }

    protected function checkAction($class_name, $action)
    {
        if (class_exists($class_name)) {
            if (method_exists($class_name, $action)) {
                return true;
            }
        }
        return false;
    }

}
