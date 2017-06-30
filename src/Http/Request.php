<?php

namespace CoreCMF\core\Http;

use Illuminate\Http\Request;

class Request
{
  private $request;
  private $response;
  /**
   * Create a new Skeleton Instance
   */
  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  /**
   * [response 数据处理返回]
   * @return   [type]                   [输出后的form数据]
   */
  public function response()
  {

      return $this->response;
  }
}
