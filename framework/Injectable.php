<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:22
 * Desc: 
 */

namespace Framework;

abstract class Injectable
{

    protected $di;

    public function setDi(Di $di)
    {
        $this->di = $di;
    }

    public function getDi()
    {
        if (!$this->di instanceof Di) {
            $this->di = Di::getDefault();
        }
        return $this->di;
    }

    public function __get($property_name)
    {
        if (!$this->di instanceof Di) {
            $this->di = Di::getDefault();
        }

        if (is_string($property_name) && $this->di->has($property_name)) {
            return $this->di->getShard($property_name);
        }
        return null;
    }

}
