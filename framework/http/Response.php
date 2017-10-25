<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:23
 * Desc: http响应
 */

namespace Framework;

class Response
{

    protected $content;
    protected $content_type;
    protected $header;
    protected $status;
    protected $is_send;

    public function __construct($content = null)
    {
        if ($content !== null) {
            $this->content = $content;
        }
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setJsonContent($content)
    {
        $this->content = json_encode($content);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function send()
    {
        echo $this->content;
    }

}
