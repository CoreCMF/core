<?php

namespace CoreCMF\core\Commands;

use Illuminate\Console\Command;

use CoreCMF\core\Support\Files;
use CoreCMF\core\Support\Commands\Uninstall;
class UninstallCommand extends Command
{
    protected $uninstall;
    protected $files;
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @translator laravelacademy.org
     */
    protected $signature = 'corecmf:core:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'core packages uninstall';

    public function __construct(Uninstall $uninstall,Files $files)
    {
        parent::__construct();
        $this->uninstall = $uninstall;
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //删除对应数据库数据
        $this->info($this->uninstall->dropTable('entrust_permission_role'));
        $this->info($this->uninstall->dropTable('entrust_permissions'));
        $this->info($this->uninstall->dropTable('entrust_role_user'));
        $this->info($this->uninstall->dropTable('entrust_roles'));
        $this->info($this->uninstall->dropTable('oauth_auth_codes'));
        $this->info($this->uninstall->dropTable('oauth_access_tokens'));
        $this->info($this->uninstall->dropTable('oauth_refresh_tokens'));
        $this->info($this->uninstall->dropTable('oauth_clients'));
        $this->info($this->uninstall->dropTable('oauth_personal_access_clients'));
        $this->info($this->uninstall->dropTable('core_uploads'));
        $this->info($this->uninstall->dropTable('core_users'));
    }
}
