<?php

namespace CoreCMF\core\Builder;

class Form
{
  private $data;
  private $apiUrl;
  private $config;
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
      return $this->data = $data;
  }
  /**
   * [apiUrl 设置 apiUrl API通信网址]
   * @param [type] $key    [通信类型]
   * @param [type] $ApiUrl [通信网址]
   */
  public function apiUrl($key, $ApiUrl){
      return $this->apiUrl[$key] = $ApiUrl;
  }
  /**
   * [config 配置参数]
   * @param  [type] $config [配置参数]
   */
  public function config($config){
      return $this->config = $config;
  }
  /**
   * [response 数据处理返回]
   * @return   [type]                   [输出后的form数据]
   */
  public function response()
  {
      $this->response['data'] = $this->data;
      $this->response['apiUrl'] = $this->apiUrl;
      $this->response['config'] = $this->config;
      return $this->response;
  }
}
