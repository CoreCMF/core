<?php

namespace CoreCMF\core\Support\Http;

use Illuminate\Http\Request as laravelRequest;

class Request
{
  private $request;
  private $response;
  /**
   * Create a new Skeleton Instance
   */
  public function __construct(laravelRequest $request)
  {
    $this->request = $request;
  }
  public function get($key, $default=null)
  {
    return empty($this->request->$key) ? $default : $this->request->$key;
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
