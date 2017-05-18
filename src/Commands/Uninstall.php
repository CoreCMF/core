<?php

namespace CoreCMF\core\Commands;

use DB;
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
    	$this->info('dropIfExists'. $name .'Table');
        Schema::dropIfExists($name);
        $this->info('delete the'. $name .'inside migrations');
        DB::table('migrations')->where('migration', 'like','%'.$name.'_table%')->delete();
    }
}
