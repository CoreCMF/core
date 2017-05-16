<?php

namespace CoreCMF\core\Builder;

class Main
{
    public $data;

    private $config;
    private $apiUrl;
    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {

    }

    public function config($key,$value)
    {
        return $this->config[$key] = $value;
    }

    public function apiUrl($key,$value)
    {
        return $this->apiUrl[$key] = $value;
    }

    public function response()
    {
        $this->data['config'] = $this->config;
        $this->data['apiUrl'] = $this->apiUrl;
        return $this->data;
    }
}
