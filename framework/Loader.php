<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/22 21:13
 * Desc: 自动加载类
 */

namespace Framework;

class loader
{
    protected $namespace_registers = [];
    protected $registers           = [];

    public function __construct()
    {

    }

    /**
     * 命名空间
     * @param string  $namespace
     * @param string  $path
     * @param boolean $uc_first 文件路径首字母是否大写
     */
    public function setNamespaceRegister($namespace, $path, $uc_first = true)
    {
        $this->namespace_registers[$namespace]['path']     = $path;
        $this->namespace_registers[$namespace]['count']    = count(explode('\\', $namespace));
        $this->namespace_registers[$namespace]['uc_first'] = $uc_first;
    }

    /**
     * 注册通用路径
     * @param string  $path
     * @param boolean $uc_first
     */
    public function setRegister($path, $uc_first = true)
    {
        $this->registers[] = ['path' => $path, 'uc_first' => $uc_first];
    }

    public function register()
    {
        spl_autoload_register(function ($class) {
            if ($class_path = $this->findFile($class)) {
                include $class_path;
                if (!class_exists($class, false)) {  // 进行一次检测
                    return false;
                }
                return true;
            }
            return false;
        });
    }

    /**
     * 查找文件的方法,首先查找框架文件,然后查找关键词路径,最后查找通用路径
     * @param string $class
     * @return boolean|string
     */
    public function findFile($class)
    {
        // 框架文件
        $file       = str_replace('\\', '/', $class);
        $class_path = ROOT_PATH . '/' . strtolower($file) . '.php';
        if (is_file($class_path)) {
            return $class_path;
        }

        $file_info = array_values(array_filter(explode('/', $file)));
        $count     = count($file_info);
        if ($count > 1) {   // 含有命名空间
            foreach ($this->namespace_registers as $k => $v) {
                if ($count <= $v['count']) {
                    continue;
                }
                $namespace      = [];
                $temp_file_info = $file_info;
                for ($i = 0; $i < $v['count']; $i++) {
                    $namespace[] = $file_info[$i];
                    unset($temp_file_info[$i]);
                }
                if ($k === implode('\\', $namespace)) {
                    array_walk($temp_file_info, function (&$string) {
                        $string = strtolower($string);
                    });
                    $temp_file_info[$count - 1] = $v['uc_first'] ? ucfirst($temp_file_info[$count - 1]) : lcfirst($temp_file_info[$count - 1]);
                    $file_path                  = ROOT_PATH . $v['path'] . implode('/', $temp_file_info) . '.php';
                    if (is_file($file_path)) {
                        return $file_path;
                    }
                }
            }
        } else {            // 通用路径
            foreach ($this->registers as $register) {
                $file      = $register['uc_first'] ? ucfirst($file) : lcfirst($file);
                $file_path = ROOT_PATH . $register['path'] . $file . '.php';
                if (is_file($file_path)) {
                    return $file_path;
                }
            }
        }
        return false;
    }

    public function getNamespace()
    {
        return array_keys($this->namespace_registers);
    }
}
