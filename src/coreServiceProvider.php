<?php

namespace CoreCMF\core;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use CoreCMF\core\Support\Builder\Html as builderHtml;
use CoreCMF\core\Support\Builder\Form as builderForm;
use CoreCMF\core\Support\Builder\Table as builderTable;
use Laravel\Passport\Passport;

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
        Schema::defaultStringLength(191);//mysql5.7一下适应Support\
        //加载artisan commands
        $this->commands($this->commands);
        // 加载配置
        $this->mergeConfigFrom(__DIR__.'/Config/config.php', 'core');
        $this->mergeConfigFrom(__DIR__.'/Config/entrust.php', 'entrust');
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/../databases/migrations');
        //发布seed填充文件
        $this->publishes([
            __DIR__.'/../databases/seeds/' => database_path('seeds')
        ], 'seeds');

        //注册providers服务
        $providers = config('core.providers');
        $this->registerProviders($providers);
        //注册Passport
        $this->registerPassport();
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

    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function registerPassport()
    {
        //注册api认证的路由
        Passport::routes();
        //修改auth api 驱动
        config(['auth.guards.api.driver' => 'passport']);
    }
}
