<?php

namespace CoreCMF\core\Builder;

class Main
{
    public $data;

    private $routes = [];
    private $config;
    private $apiUrl;
    private $topNavs;
    private $menus;
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
     * [topNav 设置顶部导航]
     * @param    [type]                   $topNav [顶部导航配置数据]
     * @return   [type]                           [description]
     */
    public function addTopNav($topNav){
        return $this->topNavs[$topNav['name']] = $topNav;
    }
    /**
     * [getTopNavs 获取顶部导航 并根据sort进行排序]
     * @return   [type]                   [description]
     */
    public function getTopNavs(){
        $topNavs = collect($this->topNavs)->sortBy('sort');
        $this->topNavs = $topNavs;
        return $this->topNavs;
    }
    /**
     * [getMenus 获取处理后的菜单数据]
     * @return [type] [description]
     */
    public function getMenus(){
      return $this->menus;
    }
    /**
     * [setMenus 批量设置导航菜单]
     * @param    [type]                   $menus [description]
     */
    public function setMenus($menus){
        $menus = $menus->map(function ($menu) {
            $menu->path = $menu->value;
            return $menu;
        })->filter(function ($menu, $key) use($menus) {
            if ($menu->pid == 0) {
                $menu = $this->getSubMenus($menu,$menus);
            }
            return $menu->pid == 0;
        });
        return $this->menus  = $menus;
    }

    /**
     * [getSubMenus 设置子导航(可无限级设置)]
     * @param    [type]                   $menu  [过滤后顶级菜单]
     * @param    [type]                   $menus [菜单原始数据]
     * @return   [type]                          [description]
     */
    public function getSubMenus($menu,$menus){
        $id = $menu->id;
        $subMenus = $menus->filter(function ($menu, $key) use($id,$menus) {
            if ($menu->pid == $id) {
                $menu = $this->getSubMenus($menu,$menus);
            }
            return $menu->pid == $id;
        });
        if (!$subMenus->isEmpty()) {
            $menu->subMenus = $subMenus;
        }
        return $menu;
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
