<?php

namespace CoreCMF\Core\Support\Events;

use Illuminate\Queue\SerializesModels;

class BuilderHtml
{
    use SerializesModels;

    public $html;

    /**
     * 创建一个新的事件实例.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct($class)
    {
        $this->html = $class;
    }
}
