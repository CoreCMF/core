<?php

namespace CoreCMF\core\Builder;

use CoreCMF\core\Builder\Form;
use CoreCMF\core\Builder\Table;

class Html
{
  private $items;
  private $title;
  private $config;
  private $tabs;

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
  public function itemConfig($key,$value){
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
   * [response 数据处理返回]
   * @return   [type]                   [description]
   */
  public function response()
  {
      $response['title']  = $this->title;
      $response['items']   = $this->items;
      $response['config']  = $this->config;

      return $response;
  }
}
