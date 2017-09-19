<?php

namespace CoreCMF\Core\Support\Builder;

use CoreCMF\Core\Support\Events\BuilderMain;

class Main
{
    private $type = 'main';
    public $event;
    public $routes = [];
    public $config;
    public $apiUrl;
    public $topNavs;
    public $menus;
    private $response;
    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {
        $this->menus = collect();
        $this->routes = collect();
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
     * [route 设置路由参数]
     * @param    [type]                   $path   [前端路由路径]
     * @param    [type]                   $name   [前端路由名称]
     * @param    [type]                   $apiUrl [路由API通信地址]
     * @param    [type]                   $children [子路由]
     * @return   [type]                           [description]
     */
    public function route($array){
        $this->routes->push([
            'path'    =>$array['path'],
            'name'    =>$array['name'],
            'meta'    =>[ 'apiUrl' => $array['apiUrl'] ],
            'children'=>$array['children'],
            'component'=>[ 'template'=> $array['component'] ],
        ]);
        return $this;
    }

    /**
     * [routes 批量设置路由参数]
     * @param    [type]                   $routes [路由参数 需要符合 function route 规范]
     * @return   [type]                           [description]
     */
    public function routes($routes){
        $this->routes = $this->routes->merge($routes);
        return $this;
    }
    /**
     * [setRouteComponent 批量设置路由 $component]
     * @param [type] $routes    [description]
     * @param [type] $component [description]
     */
    public function setRouteComponent($routes,$component){
        foreach ($routes as $key => &$route) {
            $route['component'] = [ 'template'=> $component ];
        }
        return $routes;
    }
    /**
     * [topNav 设置顶部导航]
     * @param    [type]                   $topNav [顶部导航配置数据]
     * @return   [type]                           [description]
     */
    public function topNav($topNav){
        $this->topNavs[$topNav['name']] = $topNav;
        return $this;
    }
    /**
     * [addMenus 增加菜单]
     * @param  [type] $menus [description]
     * @return [type]        [description]
     */
    public function addMenus($menus)
    {
        $this->menus = $this->menus->merge($menus);
    }
    /**
     * [setMenus 批量设置导航菜单]
     * @param    [type]                   $menus [description]
     */
    public function menus($menus){
        $menus = $menus->map(function ($menu) {
            $menu->path = $menu->value;
            return $menu;
        })->filter(function ($menu, $key) use($menus) {
            if (empty($menu->parent)) {
                $menu = $this->subMenus($menu,$menus);
            }
            return $menu->parent == null;
        });
        $this->addMenus($menus);
        return $this;
    }

    /**
     * [getSubMenus 设置子导航(可无限级设置)]
     * @param    [type]                   $menu  [过滤后顶级菜单]
     * @param    [type]                   $menus [菜单原始数据]
     * @return   [type]                          [description]
     */
    public function subMenus($menu,$menus){
        $name = $menu->name;
        $subMenus = $menus->filter(function ($menu, $key) use($name,$menus) {
            if ($menu->parent == $name) {
                $menu = $this->subMenus($menu,$menus);
            }
            return $menu->parent == $name;
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
        $this->config[$key] = $value;
        return $this;
    }

    /**
     * [apiUrl 前端API通信地址]
     * @param    [type]                   $key   [通信名称]
     * @param    [type]                   $value [apiUrl网址]
     * @return   [type]                          [description]
     */
    public function apiUrl($key,$value)
    {
        $this->apiUrl[$key] = $value;
        return $this;
    }

    /**
     * [response 数据处理返回]
     * @return   [type]                   [description]
     */
    public function response()
    {
        event(new BuilderMain($this)); //分发事件

        $this->response['type']   = $this->type;
        $this->response['routes'] = $this->routes;
        $this->response['config'] = $this->config;
        $this->response['apiUrl'] = $this->apiUrl;
        $this->response['menus']  = $this->menus;
        $this->response['topNavs']= collect($this->topNavs)->sortBy('sort');
        return $this->response;
    }
}
