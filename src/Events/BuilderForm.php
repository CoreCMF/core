<?php

namespace CoreCMF\Core\Events;

use Illuminate\Queue\SerializesModels;

class BuilderForm
{
    use SerializesModels;

    public $form;

    /**
     * 创建一个新的事件实例.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct($class)
    {
        $this->form = $class;
    }
}
