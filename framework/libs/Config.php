<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:17
 * Desc: 配置类
 */

namespace Framework;

class Config implements \ArrayAccess
{
    protected $array_config = [];

    public function __construct(array $array_config)
    {
        foreach ($array_config as $k => $value) {
            $this->offsetSet($k, $value);
        }
    }

    public function offsetExists($offset)
    {
        $index = strval($offset);
        return isset($this->array_config[$index]);
    }

    public function offsetGet($offset)
    {
        $index = strval($offset);
        return isset($this->array_config[$index]) ? $this->array_config[$index] : null;
    }

    public function offsetSet($offset, $value)
    {
        $index = strval($offset);
        if (is_array($value)) {
            $this->array_config[$index] = new self($value);
        } else {
            $this->array_config[$index] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        $index = strval($offset);
        unset($this->array_config[$index]);
    }

    public function toArray()
    {
        $array_config = [];
        foreach ($this->array_config as $k => $config) {
            if (is_object($config)) {
                $array_config[$k] = call_user_func([$config, 'toArray']);
            } else {
                $array_config[$k] = $config;
            }
        }
        return $array_config;
    }
}