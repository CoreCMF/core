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
        // 加载配置
        $this->mergeConfigFrom(__DIR__.'/Config/entrust.php', 'entrust');
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/../databases/migrations');
        //发布seed填充文件
        $this->publishes([
            __DIR__.'/../databases/seeds/' => database_path('seeds')
        ], 'seeds');
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
