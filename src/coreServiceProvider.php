<?php

namespace CoreCMF\core;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use CoreCMF\core\Support\Builder\Html as builderHtml;
use CoreCMF\core\Support\Builder\Form as builderForm;
use CoreCMF\core\Support\Builder\Table as builderTable;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
class coreServiceProvider extends ServiceProvider
{
    protected $commands = [
        \CoreCMF\core\Commands\InstallCommand::class,
        \CoreCMF\core\Commands\UninstallCommand::class,
        \Laravel\Passport\Console\InstallCommand::class,
        \Laravel\Passport\Console\ClientCommand::class,
        \Laravel\Passport\Console\KeysCommand::class
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
        $this->loadMigrationsFrom(__DIR__.'/Databases/migrations');

        $this->initService();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
      //注册自己的中间件
      $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
                ->pushMiddleware(Http\Middleware\Cors::class);
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

    public function initService()
    {
        //注册providers服务
        $this->registerProviders();
        //设置user模型位置
        config(['auth.providers.users.model' => Models\User::class]);
        //注册Passport
        $this->registerPassport();
    }
    public function registerPassport()
    {
        //注册api认证的路由
        Passport::routes();
        //修改auth api 驱动
        config(['auth.guards.api.driver' => 'passport']);
    }
    public function registerProviders()
    {
        $providers = config('core.providers');
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
}
