<?php

namespace CoreCMF\core\Commands;

use DB;
use Artisan;
use Illuminate\Support\Facades\Schema;
use JeroenG\Packager\PackagerHelper;

class Uninstall
{
		/**
		 * Packager helper class.
		 * @var object
		 */
		public $helper;
		/**
		 * Create a new command instance.
		 *
		 * @return void
		 */
		public function __construct(PackagerHelper $helper)
		{
				$this->helper = $helper;
		}

		public function dropTable($name)
    {
        Schema::dropIfExists($name);
        DB::table('migrations')->where('migration', 'like','%'.$name.'_table%')->delete();
				return 'dropIfExists '. $name .' Table';
    }
}
