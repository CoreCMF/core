<?php

namespace CoreCMF\core\Support\Builder;

use CoreCMF\core\Support\Builder\Form;
use CoreCMF\core\Support\Builder\Table;

class Html
{
    private $items;
    private $title;
    private $config;
    private $tabs;
    private $message;
    private $auth;

    public function __construct()
    {
    }
    /**
     * [item html项目]
     * @param  [type] $item [description]
     * @return [type]       [description]
     */
    public function item($item){
        $this->items[] = $item->response();
        return $this;
    }
    /**
     * [item html项目]
     * @param  [type] $item [description]
     * @return [type]       [description]
     */
    public function config($key,$value){
        $this->config[$key] = $value;
        return $this;
    }
    /**
     * [title 设置页面标题]
     * @param  [type] $title [description]
     * @return [type]        [description]
     */
    public function title($title){
        $this->title   = $title;
        return $this;
    }
    /**
     * [message 提示信息]
     * @param  [type] $message [description]
     */
    public function message($message){
        $this->message   = $message;
        return $this;
    }
    /**
     * [message 提示信息]
     * @param  [type] $message [description]
     */
    public function auth($auth){
        $this->auth   = $auth;
        return $this;
    }
    /**
     * [response 数据处理返回]
     * @return   [type]                   [description]
     */
    public function response()
    {
        $response['title']  = $this->title;
        $response['items']   = $this->items;
        $response['config']  = $this->config;
        $response['message']  = $this->message;
        $response['auth']  = $this->auth;

        return $response;
    }
}
