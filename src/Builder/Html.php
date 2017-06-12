<?php

namespace CoreCMF\core\Builder;

use CoreCMF\core\Builder\Form;
use CoreCMF\core\Builder\Table;

class Html
{
  /** @var form */
  public $form;
  public $table;

  private $title;
  private $tabs;
  private $response;

  public function __construct()
  {
    $this->form = new Form();
    $this->table = new Table();
  }
  /**
   * [title 设置页面标题]
   * @param  [type] $title [description]
   * @return [type]        [description]
   */
  public function title($title){
      return $this->title   = $title;
  }
  /**
   * [tabs description]
   * @param  [type] $tabs [description]
   */
  public function tabs($tabs){
      return $this->tabs   = $tabs;
  }
  /**
   * [response 数据处理返回]
   * @return   [type]                   [description]
   */
  public function response()
  {
      $this->response['title'] = $this->title;
      $this->response['tabs'] = $this->tabs;
      $this->response['form'] = $this->form->response();

      return $this->response;
  }
}
