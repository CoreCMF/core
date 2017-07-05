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
		protected $helper;
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
		public function providers($serviceProvider){
				$appConfigLine = '';
				foreach ($serviceProvider as $provider ) {
						$appConfigLine = $appConfigLine.'
        '.$provider.'::class,';
				}
				$this->helper->replaceAndSave(getcwd().'/config/app.php', $appConfigLine, '');
				return 'UninstallProviders';
		}
}
