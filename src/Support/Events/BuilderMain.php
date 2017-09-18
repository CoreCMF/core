<?php

namespace CoreCMF\Core\Support\Events;

use Illuminate\Queue\SerializesModels;

class BuilderMain
{
    use SerializesModels;

    public $main;

    /**
     * 创建一个新的事件实例.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct($class)
    {
        $this->main = $class;
    }
}
