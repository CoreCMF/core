<?php

namespace CoreCMF\core\Commands;

use JeroenG\Packager\PackagerHelper;
use Artisan;
/**
 * class install
 * @package CoreCMF\core\Commands
 */
class Install
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
    public function providers($serviceProvider){
        $replace = 'CoreCMF\core\coreServiceProvider::class,';
        foreach ($serviceProvider as $provider ) {
            $replace = $replace.'
        '.$provider.'::class,';
        }
        $this->helper->replaceAndSave(getcwd().'/config/app.php', 'CoreCMF\core\coreServiceProvider::class,', $replace);
        return 'installProviders';
    }
}
