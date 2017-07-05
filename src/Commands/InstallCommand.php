<?php

namespace CoreCMF\core\Commands;

use Illuminate\Console\Command;

use CoreCMF\core\Support\Files;
use CoreCMF\core\Support\Commands\Install;

class InstallCommand extends Command
{
    /**
     *  install class.
     * @var object
     */
    protected $install;
    protected $files;
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

    public function __construct(Install $install,Files $files)
    {
        parent::__construct();
        $this->install = $install;
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info($this->install->migrate());
        $this->info($this->install->publish('seeds'));
        $this->info($this->install->dumpAutoload());
        $this->info($this->install->seed('CoreUserTableSeeder'));
    }
}
