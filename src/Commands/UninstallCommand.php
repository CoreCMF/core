<?php

namespace CoreCMF\core\Commands;

use Illuminate\Console\Command;

use CoreCMF\core\Commands\Uninstall;
class UninstallCommand extends Command
{
    protected $uninstall;
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

    public function __construct(Uninstall $uninstall)
    {
        parent::__construct();
        $this->uninstall = $uninstall;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->unisntallPassport();
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
    /**
     * [isntallPassport 卸载Passport API认证]
     * @return [type] [description]
     */
    public function unisntallPassport()
    {
        $replace = 'use Illuminate\Support\Facades\Gate;';
        $search = 'use Laravel\Passport\Passport;'."\r\n".
        'use Illuminate\Support\Facades\Gate;';
        $this->uninstall->helper->replaceAndSave(
          getcwd().'/app/Providers/AuthServiceProvider.php',
          $search,
          $replace
        );

        $replace = '$this->registerPolicies();';
        $search = '$this->registerPolicies();
        Passport::routes();';
        $this->uninstall->helper->replaceAndSave(
          getcwd().'/app/Providers/AuthServiceProvider.php',
          $search,
          $replace
        );

        $replace = "'driver' => 'token',";
        $search = "'driver' => 'passport',";
        $this->uninstall->helper->replaceAndSave(
          getcwd().'/config/auth.php',
          $search,
          $replace
        );
    }
}
