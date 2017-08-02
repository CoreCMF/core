<?php

namespace CoreCMF\core;

use Route;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use CoreCMF\core\Support\Builder\Html as builderHtml;
use CoreCMF\core\Support\Builder\Form as builderForm;
use CoreCMF\core\Support\Builder\Table as builderTable;
use CoreCMF\core\Support\Contracts\Prerequisite;
use CoreCMF\core\Support\Prerequisite\Composite;
use CoreCMF\core\Support\Prerequisite\PhpExtension;
use CoreCMF\core\Support\Prerequisite\PhpVersion;
use CoreCMF\core\Support\Prerequisite\WritablePath;

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
        //设置发布前端文件
        $this->publishes([
            __DIR__.'/../resources/vendor/' => public_path('vendor'),
        ], 'core');
        $this->initService();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
      $this->middleware();

      $this->app->bind('builderHtml', function () {
          return new builderHtml();
      });
      $this->app->bind('builderForm', function () {
          return new builderForm();
      });
      $this->app->bind('builderTable', function () {
          return new builderTable();
      });
      $this->app->singleton(Prerequisite::class, function () {
          return new Composite(
            new PhpVersion(config('corecmf.prerequisite.phpVersion')),
            new PhpExtension(config('corecmf.prerequisite.phpExtension')),
            new WritablePath(config('corecmf.prerequisite.writablePath'))
          );
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
    public function middleware()
    {
        //注册跨域控制中间件
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
                  ->pushMiddleware(Http\Middleware\Cors::class);
        //注册 Passport JavaScript消费API 中间件
        Route::pushMiddlewareToGroup('web', \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class);
    }
}
