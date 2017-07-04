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
        $this->info('dumpAutoload');
        exec('composer dumpautoload');
    }
	  public function migrate()
    {
        $this->info('migrate');
        Artisan::call('migrate');
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
    public function installProviders($serviceProvider){
        $appConfigLine = 'App\Providers\RouteServiceProvider::class,';
        foreach ($serviceProvider as $provider ) {
            $appConfigLine = $appConfigLine.'
        '.$provider.',';
        }
        dd($this->helper);
        // $this->helper->replaceAndSave(getcwd().'/config/app.php', 'App\Providers\RouteServiceProvider::class,', $appConfigLine);
    }
}
