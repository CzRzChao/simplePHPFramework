<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/22 22:46
 * Desc: application
 */

namespace Framework;

class Application
{

    protected $uri;
    protected $dispatcher;
    protected $di;
    protected $loader;
    protected $response;
    protected $is_json = false;

    public function __construct()
    {
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', realpath(dirname(__DIR__)));
        }
        include ROOT_PATH . '/framework/loader.php';
        $this->loader = new Loader();
        $this->loader->register();
    }

    /**
     * 初始化
     */
    protected function _init()
    {
        if (empty($this->di)) {
            $this->di = new DefaultFactory();
        }

        if (empty($this->uri)) {
            $this->uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        }

        if (empty($this->response)) {
            $this->response = $this->di->getShard('response');
        }

        if (empty($this->dispatcher)) {
            $this->dispatcher = $this->di->getShard('dispatcher');
            $this->dispatcher->setNamespace($this->loader->getNamespace());
        }
    }

    /**
     * application处理主要方法
     * @param string $uri
     * @return Response
     */
    public function handle($uri = null)
    {
        if (!empty($uri)) {
            $this->uri = $uri;
        }

        try {
            $this->_init();
            $possible_response = $this->_dispatch();
            if ($possible_response instanceof Response) {
                $this->response = $possible_response;
            } else {
                if ($this->is_json) {
                    $this->response->setJsonContent($possible_response);
                } else {
                    $this->response->setContent($possible_response);
                }
            }
        } catch (\Exception $ex) {
            $this->response->setException($ex->getCode(), $ex->getMessage());
        }
        return $this->response;
    }

    public function getLoader()
    {
        return $this->loader;
    }

    public function setDi(Di $di)
    {
        if (!empty($di)) {
            $this->di = $di;
        }
        return $this;
    }

    public function setJson($is_json)
    {
        $this->is_json = $is_json;
        return $this;
    }

    protected function _preDispatch()
    {
        // 可以进行token判断或者登陆判断之类的操作
    }

    /**
     * 路由分发
     */
    protected function _dispatch()
    {
        $this->_preDispatch();
        return $this->dispatcher->dispatch($this->uri);
    }

}