<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:24
 * Desc: 请求
 */

class Request
{

    public function get($key, $default_value = null)
    {
        if (isset($_GET[$key])) {
            return $key;
        }
        return $default_value;
    }

    public function getPost($key, $default_value = null)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return $default_value;
    }

    public function has($key)
    {
        return isset($_REQUEST[$key]);
    }
}