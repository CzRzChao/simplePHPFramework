<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:19
 * Desc: service类 di注入的实例类
 */

namespace Framework;

class Service
{

    protected $name;
    protected $definition;
    protected $shard_instance;
    protected $is_resolved;
    protected $is_shared;

    public function __construct($name, $definition, $is_shared = true)
    {
        $this->name           = $name;
        $this->definition     = $definition;
        $this->shard_instance = null;
        $this->is_resolved    = false;
        $this->is_shared      = $is_shared;
    }

    public function resolve(array $params = [])
    {
        if ($this->is_shared && $this->is_resolved) {
            return $this->shard_instance;
        }

        $found = true;
        // 字符串当做类名处理
        if (is_string($this->definition)) {
            if (class_exists($this->definition)) {
                $ref = new \ReflectionClass($this->definition);
                if (count($params) > 0) {
                    $this->shard_instance = $ref->newInstanceArgs($params);
                } else {
                    $this->shard_instance = $ref->newInstance();
                }
            } else {
                $found = false;
            }
        } else if (is_object($this->definition)) {
            if ($this->definition instanceof \Closure) {    // 闭包
                if (count($params) > 0) {
                    $this->shard_instance = call_user_func_array($this->definition, $params);
                } else {
                    $this->shard_instance = call_user_func($this->definition);
                }
            } else {    // 函数注入
                $this->shard_instance = $this->definition;
            }
        } else if (is_array($this->definition)) {
            $this->shard_instance = $this->definition;
        } else {
            $found = false;
        }

        if (!$found) {
            throw new Exception(Exception::CLASS_CREATE_FAILED, "$this->definition can't be resolve");
        }
        $this->is_resolved = true;
        return $this->shard_instance;
    }

}
