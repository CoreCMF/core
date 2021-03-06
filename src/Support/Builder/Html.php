<?php

namespace CoreCMF\Core\Support\Builder;

use CoreCMF\Core\Support\Events\BuilderHtml;

class Html
{
    private $items;
    private $event;
    private $main;
    private $title;
    private $config;
    private $tabs;
    private $message;
    private $auth;
    private $callback;
    private $withCode = 200;
    private $cookie = null;

    public function __construct()
    {
    }
    /**
     * [event 绑定fMain事件]
     * @param  [type] $event [事件]
     * @return [type]        [description]
     */
    public function event($event)
    {
        $this->event = $event;
        return $this;
    }
    /**
     * [item html项目]
     * @param  [type] $item [description]
     * @return [type]       [description]
     */
    public function item($item)
    {
        $this->items[] = $item->response();
        return $this;
    }
    public function main($main)
    {
        $this->main = $main->response();
        return $this;
    }
    public function config($key, $value)
    {
        $this->config[$key] = $value;
        return $this;
    }
    /**
     * [title 设置页面标题]
     * @param  [type] $title [description]
     * @return [type]        [description]
     */
    public function title($title)
    {
        $this->title   = $title;
        return $this;
    }
    /**
     * [message 提示信息]
     * @param  [type] $message [description]
     */
    public function message($message)
    {
        $this->message   = $message;
        return $this;
    }
    /**
     * [auth 用户认证信息]
     * @param  [type] $auth [description]
     */
    public function auth($auth)
    {
        $this->auth   = $auth;
        return $this;
    }
    /**
     * [callback 回调信息]
     * @param  [type] $callback [description]
     */
    public function callback($callback)
    {
        $this->callback   = $callback;
        return $this;
    }
    /**
     * [withCode 返回代码状态]
     * @param  [type] $withCode [description]
     */
    public function withCode($withCode)
    {
        $this->withCode   = $withCode;
        return $this;
    }
    public function cookie($cookie)
    {
        $this->cookie   = $cookie;
        return $this;
    }
    /**
     * [response 数据处理返回]
     * @return   [type]                   [description]
     */
    public function response()
    {
        event(new BuilderHtml($this)); //分发事件

        $response['title']   = $this->title;
        $response['items']   = $this->items;
        $response['main']    = $this->main;
        $response['config']  = $this->config;
        $response['message'] = $this->message;
        $response['auth']    = $this->auth;
        $response['callback']= $this->callback;
        if ($this->cookie) {
            return response()->json($response, $this->withCode)->cookie($this->cookie);
        }
        return response()->json($response, $this->withCode);
    }
}
