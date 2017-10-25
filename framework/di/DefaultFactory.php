<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:18
 * Desc: 默认的依赖注入工厂
 */

namespace Framework;

class DefaultFactory extends Di
{

    public function __construct()
    {
        parent::__construct();
        $this->services = [
            'router'     => new Service('router', 'Framework\Router'),
            'dispatcher' => new Service('dispatcher', 'Framework\Dispatcher'),
            'response'   => new Service('response', 'Framework\Response'),
            'exception'  => new Service('exception', 'Framework\SimpleException'),
        ];
    }

}
