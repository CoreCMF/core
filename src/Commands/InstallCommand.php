<?php

namespace CoreCMF\Core\Commands;

use Artisan;
use Illuminate\Console\Command;

use CoreCMF\Core\Support\Commands\Install;

class InstallCommand extends Command
{
    /**
     *  install class.
     * @var object
     */
    protected $install;
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @translator laravelacademy.org
     */
    protected $signature = 'corecmf:core:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'core packages install';

    public function __construct(Install $install)
    {
        parent::__construct();
        $this->install = $install;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info($this->install->migrate());
        $this->info($this->install->publish('core'));
        $this->info($this->install->seed(\CoreCMF\Core\Databases\seeds\RoleTableSeeder::class));
        $this->info($this->install->seed(\CoreCMF\Core\Databases\seeds\UploadTableSeeder::class));
        //安装passport
        $this->info('passport install');
        Artisan::call('passport:install');
    }
}
