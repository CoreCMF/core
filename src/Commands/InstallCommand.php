<?php

namespace CoreCMF\core\Commands;

use Illuminate\Console\Command;

use CoreCMF\core\Commands\Install;

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

    private $providers = [
        'JeroenG\Packager\PackagerServiceProvider::class',  //包开发
        'Laravel\Passport\PassportServiceProvider::class',  //api管理
        'Zizaco\Entrust\EntrustServiceProvider::class',     //权限管理
    ];

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
        $this->installationService();
        $this->info($this->install->migrate());
        $this->info($this->install->publish('seeds'));
        $this->info($this->install->dumpAutoload());
        $this->info($this->install->seed('CoreUserTableSeeder'));
    }
    /**
     * [installationService 安装相关依赖服务]
     */
    public function installationService()
    {
        $this->info($this->install->installProviders($this->providers));
    }
}
