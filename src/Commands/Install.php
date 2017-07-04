<?php

namespace CoreCMF\core\Commands;

use JeroenG\Packager\PackagerHelper;
use Artisan;
/**
 * trait install
 * @package CoreCMF\core\Commands
 */
class Install
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
    public function dumpAutoload()
    {
        exec('composer dumpautoload');
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
    public function installProviders($serviceProvider){
        $appConfigLine = 'CoreCMF\core\coreServiceProvider::class,';
        foreach ($serviceProvider as $provider ) {
            $appConfigLine = $appConfigLine.'
        '.$provider.',';
        }
        $this->helper->replaceAndSave(getcwd().'/config/app.php', 'CoreCMF\core\coreServiceProvider::class,', $appConfigLine);
        return 'installProviders';
    }
}
