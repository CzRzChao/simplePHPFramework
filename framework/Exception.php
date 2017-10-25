<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:20
 * Desc: 
 */

namespace Framework;

class SimpleException extends \Exception
{

    const ROUTER_FAILED       = 10001;
    const CLASS_CREATE_FAILED = 10002;

    public static $messages = array(
        self::ROUTER_FAILED       => '路由失败',
        self::CLASS_CREATE_FAILED => '创建类失败',
    );

    public function __construct($code, $message = "")
    {
        if (empty($message) && isset(self::$messages[$code])) {
            $message = self::$messages[$code];
        }
        parent::__construct($message, $code);
    }

}
