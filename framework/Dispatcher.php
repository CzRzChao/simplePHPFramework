<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:21
 * Desc:
 */

namespace Framework;

class Dispatcher extends Injectable
{

    protected $is_finished        = false;
    protected $default_namespace  = [];
    protected $default_controller = 'index';
    protected $default_action     = 'index';
    protected $namespaces         = [];
    protected $controller;
    protected $action;

    public function __construct(array $config = [])
    {
        if (isset($config['default_namespace'])) {
            $this->default_namespace = explode('\\', $config['default_namespace']);
        }
        if (isset($config['default_controller'])) {
            $this->default_controller = $config['default_controller'];
        }
        if (isset($config['default_action'])) {
            $this->default_action = $config['default_action'];
        }
    }

    public function dispatch($uri)
    {
        $loop_num = 0;
        $content  = '';
        while (!$this->is_finished) {   // 分发循环
            if (++$loop_num > 256) {
                throw new Exception(Exception::ROUTER_FAILED, '分发次数过多');
            }

            $this->router->handle($uri);
            $handle  = $this->router->match();
            $content = $this->_handle($handle['class_name'], $handle['action']);
        }
        return $content;
    }

    public function forward($controller, $action, $params = [])
    {
        $this->is_finished = false;
    }

    protected function _handle($class_name, $action, $params = [])
    {
        $this->is_finished = true;
        $class             = new $class_name();
        return call_user_func_array([$class, $action], $params);
    }

    public function setNamespace($namespace)
    {
        if (is_string($namespace)) {
            $namespace = [$namespace];
        }
        foreach ($namespace as $it) {
            is_string($it) && $this->namespaces[] = $it;
        }
    }

    public function getDefaultHandle()
    {
        return [
            'default_namespace' => $this->default_namespace,
            'controller'        => $this->default_controller,
            'action'            => $this->default_action,
            'namespaces'        => $this->namespaces,
        ];
    }

}
