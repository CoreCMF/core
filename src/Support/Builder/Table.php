<?php

namespace CoreCMF\Core\Support\Builder;

use CoreCMF\Core\Support\Events\BuilderTable;

class Table
{
  private $type = 'table';
  public  $event;
  public  $stripe = true;
  public  $tabs;
  public  $defaultTabs;
  public  $data;
  public  $column;
  public  $topButton;
  public  $rightButton;
  public  $pagination;
  public  $searchTitle;
  public  $searchSelect;
  /**
   * Create a new Skeleton Instance
   */
  public function __construct()
  {

  }
  /**
   * [event 绑定form事件]
   * @param  [type] $event [事件]
   * @return [type]        [description]
   */
  public function event($event)
  {
    $this->event = $event;
    return $this;
  }
  /**
   * [tabs description]
   */
  public function tabs($tabs){
      $this->tabs   = $tabs;
      return $this;
  }
  /**
   * [defaultTabs tabs分组字段]
   */
  public function defaultTabs($name){
      $this->defaultTabs   = $name;
      return $this;
  }
  /**
   * [data 设置数据源]
   * @param  [type] $data [数据对象]
   */
  public function data($data){
      $this->data = $data;
      return $this;
  }
  public function column($column){
      $this->column[] = $column;   //设置 tableColumn 表格柱
      return $this;
  }
  public function topButton($topButton){
      $this->topButton[]   = $topButton;   //设置 table rightButton 表格右侧按钮
      return $this;
  }
  public function rightButton($rightButton) {
      $this->rightButton[]   = $rightButton;   //设置 table rightButton 表格右侧按钮
      return $this;
  }
  public function closeStripe($column) {
      $this->stripe = false;   //关闭斑马线显示
      return $this;
  }
  public function pagination($pagination) {
      if(empty($pagination['layout'])){
          $pagination['layout'] = 'total, sizes, prev, pager, next, jumper';
      }
      $this->pagination = $pagination;
      return $this;
  }
  public function searchTitle($title) {
      $this->searchTitle = $title;
      return $this;
  }
  public function searchSelect($select) {
      $this->searchSelect = $select;
      return $this;
  }
  /**
   * [response 数据处理返回]
   * @return   [type]                   [输出后的form数据]
   */
  public function response()
  {
      event(new BuilderTable($this)); //分发事件

      $response['type']       = $this->type;
      $response['stripe']     = $this->stripe;
      $response['tabs']       = $this->tabs;
      $response['defaultTabs']  = $this->defaultTabs;
      $response['data']       = $this->data;
      $response['column']     = $this->column;
      $response['topButton']  = $this->topButton;
      $response['rightButton']= $this->rightButton;
      $response['pagination'] = $this->pagination;
      $response['search']['title'] = $this->searchTitle;
      $response['search']['select'] = $this->searchSelect;
      return $response;
  }
}
