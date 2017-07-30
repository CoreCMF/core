<?php

namespace CoreCMF\core\Support\Builder;

use CoreCMF\core\Support\Builder\Form;
use CoreCMF\core\Support\Builder\Table;

class Html
{
    private $items;
    private $main;
    private $title;
    private $config;
    private $tabs;
    private $message;
    private $auth;
    private $callback;
    private $withCode = 200;

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
    public function main($main){
        $this->main = $main->response();
        return $this;
    }
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
     * [auth 用户认证信息]
     * @param  [type] $auth [description]
     */
    public function auth($auth){
        $this->auth   = $auth;
        return $this;
    }
    /**
     * [callback 回调信息]
     * @param  [type] $callback [description]
     */
    public function callback($callback){
        $this->callback   = $callback;
        return $this;
    }
    /**
     * [withCode 返回代码状态]
     * @param  [type] $withCode [description]
     */
    public function withCode($withCode){
        $this->withCode   = $withCode;
        return $this;
    }
    /**
     * [response 数据处理返回]
     * @return   [type]                   [description]
     */
    public function response()
    {
        $response['title']   = $this->title;
        $response['items']   = $this->items;
        $response['main']    = $this->main;
        $response['config']  = $this->config;
        $response['message'] = $this->message;
        $response['auth']    = $this->auth;
        $response['callback']= $this->callback;

        return response()->json($response, $this->withCode);
    }
}
