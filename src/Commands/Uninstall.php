<?php

namespace CoreCMF\core\Commands;

use Artisan;
use Illuminate\Support\Facades\Schema;
/**
 * trait Uninstall
 * @package CoreCMF\core\Commands
 */
trait Uninstall
{
	public function dropTable($name)
    {
        Schema::dropIfExists($name);
    }
}
