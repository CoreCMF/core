<?php

namespace CoreCMF\core\Commands;

use Artisan;
/**
 * trait install
 * @package CoreCMF\core\Commands
 */
trait Install
{
		public function migrate()
    {
        $this->info('migrate');
        Artisan::call('migrate');
    }
		public function dumpAutoload()
    {
        $this->info('composer dump-autoload');
        Artisan::call('composer dump-autoload');
    }
    public function publish($value)
    {
    	$this->info('vendor:publish --tag='.$value.' --force');
        Artisan::call('vendor:publish', [
            '--tag' => $value,'--force' => true
        ]);
    }
    public function seed($class)
    {
        $this->info('db:seed --class='.$class);
        Artisan::call('db:seed', [
            '--class' => $class
        ]);
    }
}
