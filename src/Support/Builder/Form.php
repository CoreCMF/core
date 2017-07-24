<?php

namespace CoreCMF\core\Support\Builder;

class Form
{
  private $type = 'form';
  private $data;
  private $apiUrl;
  private $config;
  private $rules;
  private $tabs;
  private $tabsGroup;
  private $response;
  public  $itemType = [
          'hidden'     => '隐藏',
          'static'     => '不可修改文本',
          'number'     => '数字',
          'price'      => '价格',
          'text'       => '单行文本',
          'textarea'   => '多行文本',
          'array'      => '数组',
          'password'   => '密码',
          'radio'      => '单选按钮',
          'checkbox'   => '复选框',
          'select'     => '下拉框',
          'icon'       => '字体图标',
          'date'       => '日期',
          'datetime'   => '时间',
          'picture'    => '单张图片',
          'pictures'   => '多张图片',
          'file'       => '单个文件',
          'files'      => '多个文件',
          'kindeditor' => 'HTML编辑器 kindeditor',
          'editormd'   => 'Markdown编辑器 editormd',
          'tags'       => '标签',
          'board  '    => '拖动排序',
  ];

  public function __construct()
  {

  }

  /**
   * [item 设置数据源]
   */
  public function item($item){
      $this->data[] = $item;
      return $this;
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
   * [setFormObject 给form Item 列赋值value]
   * loadAttribute 加载指定对象元素赋值 （最高支持二级对象）
   */
  public function itemData($Object){
       foreach ($this->data as &$item) {
              if (!isset($item['value'])) {
                  @$item['value'] = $Object[$item['name']];
              }
              if (isset($item['loadAttribute'])) {
                  $loadAttribute = collect($item['loadAttribute'])
                      ->map(function ($value) {
                          return explode('.',$value);
                      });
                  foreach ($loadAttribute as $key => $value) {
                      if($key){
                          @$item[$key] = $Object[$value[0]][$value[1]];
                      }else{
                          @$item['value'] = $Object[$value[0]][$value[1]];
                      }
                  }
              }
       }
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
  public function rules($rules){
    $this->rules = $rules;
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
   * [tabsGroup tabs分组字段]
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
      $this->response['tabs']       = $this->tabs;
      $this->response['tabsGroup']  = $this->tabsGroup;
      $this->response['data']       = $this->data;
      $this->response['apiUrl']     = $this->apiUrl;
      $this->response['config']     = $this->config;
      $this->response['rules']      = $this->rules;

      return $this->response;
  }
}
