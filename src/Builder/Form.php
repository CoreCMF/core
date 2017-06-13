<?php

namespace CoreCMF\core\Builder;

class Form
{
  private $type = 'form';
  private $data;
  private $apiUrl;
  private $config;
  private $tabs;
  private $tabsGroup;
  private $response;

  public function __construct()
  {

  }

  /**
   * [data 设置数据源]
   * @param  [type] $data [数据对象]
   * @return [type]       [description]
   */
  public function data($data){
      $this->data = $data;
      return $this;
  }
  /**
   * [apiUrl 设置 apiUrl API通信网址]
   * @param [type] $key    [通信类型]
   * @param [type] $ApiUrl [通信网址]
   */
  public function apiUrl($key, $ApiUrl){
      $this->apiUrl[$key] = $ApiUrl;
      return $this;
  }
  /**
   * [config 配置参数]
   * @param  [type] $config [配置参数]
   */
  public function config($key, $value){
      $this->config[$key] = $value;
      return $this;
  }
  /**
   * [tabs description]
   * @param  [type] $tabs [description]
   */
  public function tabs($tabs){
      $this->tabs   = $tabs;
      return $this;
  }
  /**
   * [tabsGroup tabs分组字段]
   * @param  [type] $name [字段名]
   * @return [type]       [description]
   */
  public function tabsGroup($name){
      $this->tabsGroup   = $name;
      return $this;
  }
  /**
   * [response 数据处理返回]
   * @return   [type]                   [输出后的form数据]
   */
  public function response()
  {
      $this->response['type']       = $this->type;
      $this->response['data']       = $this->data;
      $this->response['tabs']       = $this->tabs;
      $this->response['tabsGroup']  = $this->tabsGroup;
      $this->response['apiUrl']     = $this->apiUrl;
      $this->response['config']     = $this->config;
      return $this->response;
  }
}
