<?php

namespace CoreCMF\core\Support\Builder;

class Table
{
  private $type = 'table';
  private $stripe = true;
  private $tabs;
  private $defaultTabs;
  private $data;
  private $column;
  private $topButton;
  private $rightButton;
  private $pagination;
  private $searchTitle;
  private $searchSelect;
  /**
   * Create a new Skeleton Instance
   */
  public function __construct()
  {

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
