<?php

namespace CoreCMF\core\Commands;

use DB;
use Artisan;
use Illuminate\Support\Facades\Schema;

class Uninstall
{
	public function dropTable($name)
    {
        Schema::dropIfExists($name);
        DB::table('migrations')->where('migration', 'like','%'.$name.'_table%')->delete();
				return 'dropIfExists'. $name .'Table
				delete the'. $name .'inside migrations';
    }
}
