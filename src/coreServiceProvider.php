<?php

namespace CoreCMF\core;

use Illuminate\Support\ServiceProvider;
use CoreCMF\core\Builder\Html as builderHtml;
use CoreCMF\core\Builder\Form as builderForm;
use CoreCMF\core\Builder\Table as builderTable;

class coreServiceProvider extends ServiceProvider
{
    protected $commands = [
        'CoreCMF\core\Commands\InstallCommand',
        'CoreCMF\core\Commands\UninstallCommand',
    ];
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //加载artisan commands
        $this->commands($this->commands);
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/../databases/migrations');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind('builderHtml', function () {
          return new builderHtml();
      });
      $this->app->bind('builderForm', function () {
          return new builderForm();
      });
      $this->app->bind('builderTable', function () {
          return new builderTable();
      });
    }
}
