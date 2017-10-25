<?php
/**
 * Copyright © czrzchao.com
 * User: czrzchao
 * Date: 2017/10/23 21:23
 * Desc: 
 */

namespace Framework;

class Response
{

    protected $content;
    protected $content_type;
    protected $header;
    protected $status;
    protected $is_send;

    public function __construct($content = null, $code = null, $status = null)
    {
        if ($content !== null) {
            $this->content = $content;
        }
        if () {

        }
    }

    public function setContentType($content_type)
    {
        $this->content_type = $content_type;
    }

    public function setHeader($header)
    {
        $this->header = $header;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setJsonContent($content)
    {
        $this->content = json_encode($content);
    }

    public function setException($code, $message)
    {
        $this->setJsonContent(['code' => $code, 'message' => $message]);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function send()
    {
        if () {

        }
    }

}
