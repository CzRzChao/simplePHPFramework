<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:18
 * Desc: 依赖注入
 */

namespace Framework;

class Di implements \ArrayAccess
{

    protected static $default;
    protected        $services = [];

    public function __construct()
    {
        if (!self::$default instanceof Di) {
            self::$default = $this;
        }
    }

    public static function getDefault()
    {
        return self::$default;
    }

    public static function setDefault(Di $default)
    {
        self::$default = $default;
    }

    public function has($name)
    {
        if (isset($this->services[$name])) {
            return true;
        }
        return false;
    }

    public function get($name, array $params = [])
    {
        if (isset($this->services[$name])) {
            return $this->services[$name]->resolve($params);
        }
        return false;
    }

    public function setShard($name, $definition, $cover = true)
    {
        return $this->generalSet($name, $definition, $cover, true);
    }

    public function set($name, $definition, $cover = true)
    {
        return $this->generalSet($name, $definition, $cover, false);
    }

    public function remove($name)
    {
        if (isset($this->services[$name])) {
            unset($this->services[$name]);
        }
    }

    public function offsetExists($name)
    {
        return isset($this->services[strval($name)]);
    }

    public function offsetGet($name)
    {
        $index = strval($name);
        return isset($this->services[$index]) ? $this->services[$index] : null;
    }

    public function offsetSet($name, $value)
    {
        $index = strval($name);
        if (is_array($value)) {
            $this->services[$index] = new self($value);
        } else {
            $this->services[$index] = $value;
        }
    }

    public function offsetUnset($name)
    {
        $index = strval($name);
        unset($this->services[$index]);
    }

    /**
     * 通用的set方法
     * @param string $name
     * @param callable $definition
     * @param bool $cover 是否覆盖原有service
     * @param bool $is_shared   是否为共享service
     * @return bool
     */
    protected function generalSet($name, $definition, $cover, $is_shared)
    {
        if (isset($this->services[$name]) && !$cover) {
            return false;
        }
        $this->services[$name] = new Service($name, $definition, $is_shared);
        return true;
    }
}
