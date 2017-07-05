<?php

namespace CoreCMF\core\Support\Commands;

use Artisan;
/**
 * class install
 * @package CoreCMF\core\Commands
 */
class Install
{
    public function dumpAutoload()
    {
        shell_exec('composer dump-autoload');
        return 'dumpAutoload';
    }
	  public function migrate()
    {
        Artisan::call('migrate');
        return 'migrate';
    }
    public function publish($value)
    {
        Artisan::call('vendor:publish', [
            '--tag' => $value,'--force' => true
        ]);
        return 'vendor:publish --tag='.$value.' --force';
    }
    public function seed($class)
    {
        Artisan::call('db:seed', [
            '--class' => $class
        ]);
        return 'db:seed --class='.$class;
    }
}
