<?php

namespace CoreCMF\core\Builder;

class Main
{
    public $data;

    private $routes = [];
    private $config;
    private $apiUrl;
    private $topNavs;
    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {

    }

    /**
     * [route 设置路由参数]
     * @param    [type]                   $path   [前端路由路径]
     * @param    [type]                   $name   [前端路由名称]
     * @param    [type]                   $apiUrl [路由API通信地址]
     * @return   [type]                           [description]
     */
    public function route($path,$name,$apiUrl){
        return $this->routes[] = ['path'=>$path, 'name'=>$name, 'apiUrl'=>$apiUrl];
    }

    /**
     * [routes 批量设置路由参数]
     * @param    [type]                   $routes [路由参数 需要符合 function route 规范]
     * @return   [type]                           [description]
     */
    public function routes($routes){
        return $this->routes = array_merge($this->routes, $routes);
    }
    /**
     * [topNavDefaultActive top 导航默认激活菜单名]
     * @param  [type] $defaultActive [name]
     * @return [type]                [description]
     */
    public function topNavDefaultActive($defaultActive){
        return $this->topNavs['defaultActive'] = $defaultActive;
    }
    /**
     * [topNav 设置顶部导航]
     * @param    [type]                   $topNav [顶部导航配置数据]
     * @return   [type]                           [description]
     */
    public function topNavList($topNav){
        return $this->topNavs['list'][] = $topNav;
    }
    /**
     * [getTopNavs 获取顶部导航 并根据sort进行排序]
     * @return   [type]                   [description]
     */
    public function getTopNavs(){
        $topNavsList = collect($this->topNavs['list'])->sortBy('sort')->values()->all();
        $this->topNavs['list'] = $topNavsList;
        return $this->topNavs;
    }
    /**
     * [config 前端主要配置参数]
     * @param    [type]                   $key   [配置名称]
     * @param    [type]                   $value [配置值]
     * @return   [type]                          [description]
     */
    public function config($key,$value)
    {
        return $this->config[$key] = $value;
    }

    /**
     * [apiUrl 前端API通信地址]
     * @param    [type]                   $key   [通信名称]
     * @param    [type]                   $value [apiUrl网址]
     * @return   [type]                          [description]
     */
    public function apiUrl($key,$value)
    {
        return $this->apiUrl[$key] = $value;
    }

    /**
     * [response 数据处理返回]
     * @author BigRocs
     * @email    bigrocs@qq.com
     * @DateTime 2017-05-20T10:11:40+0800
     * @return   [type]                   [description]
     */
    public function response()
    {
        $this->data['routes'] = $this->routes;
        $this->data['config'] = $this->config;
        $this->data['apiUrl'] = $this->apiUrl;
        return $this->data;
    }
}
