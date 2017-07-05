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
        $this->isntallPassport();
        $this->info($this->install->migrate());
        $this->info($this->install->publish('seeds'));
        $this->info($this->install->dumpAutoload());
        $this->info($this->install->seed('CoreUserTableSeeder'));
    }
    /**
     * [isntallPassport 安装Passport API认证]
     * @return [type] [description]
     */
    public function isntallPassport()
    {
        $search = 'use Illuminate\Support\Facades\Gate;';
        $replace = 'use Laravel\Passport\Passport;'."\r\n".
        'use Illuminate\Support\Facades\Gate;';
        $this->install->helper->replaceAndSave(
          getcwd().'/app/Providers/AuthServiceProvider.php',
          $search,
          $replace
        );

        $search = '$this->registerPolicies();';
        $replace = '$this->registerPolicies();
        Passport::routes();';
        $this->install->helper->replaceAndSave(
          getcwd().'/app/Providers/AuthServiceProvider.php',
          $search,
          $replace
        );

        $search = "'driver' => 'token',";
        $replace = "'driver' => 'passport',";
        $this->install->helper->replaceAndSave(
          getcwd().'/config/auth.php',
          $search,
          $replace
        );
    }
}
