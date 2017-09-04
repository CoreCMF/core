<?php

namespace CoreCMF\Core\Support\Builder;

use CoreCMF\Core\Events\BuilderResources;

class Asset
{
  public $js;
  public $css;
  public $html;

  public function __construct()
  {
  }

  public function js($js)
  {
      $this->js[] = $js;
      return $this;
  }

  public function css($css)
  {
      $this->css[] = $css;
      return $this;
  }

  public function html($html)
  {
      $this->html[] = $html;
      return $this;
  }
  /**
   * [response 数据处理返回]
   * @return   [type]                   [输出后的form数据]
   */
  public function response()
  {
      $this->response['css']     = $this->css;
      $this->response['js']      = $this->js;
      $this->response['html']    = $this->html;

      return $this->response;
  }
}
