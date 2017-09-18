<?php

namespace CoreCMF\Core;

use Route;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use CoreCMF\Core\Support\Builder\Html as builderHtml;
use CoreCMF\Core\Support\Builder\Form as builderForm;
use CoreCMF\Core\Support\Builder\Table as builderTable;
use CoreCMF\Core\Support\Builder\Model as builderModel;
use CoreCMF\Core\Support\Builder\Asset as builderAsset;
use CoreCMF\Core\Support\Contracts\Prerequisite;
use CoreCMF\Core\Support\Prerequisite\Composite;
use CoreCMF\Core\Support\Prerequisite\PhpExtension;
use CoreCMF\Core\Support\Prerequisite\PhpVersion;
use CoreCMF\Core\Support\Prerequisite\WritablePath;
use CoreCMF\Core\Http\Models\Package;

class coreServiceProvider extends ServiceProvider
{
    protected $commands = [
        \CoreCMF\Core\Http\Console\InstallCommand::class,
        \CoreCMF\Core\Http\Console\UninstallCommand::class,
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
        //视图路由
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'core');
        //设置发布前端文件
        $this->publishes([
            __DIR__.'/../resources/storage/' => public_path('storage'),
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
      $this->app->bind('builderModel', function () {
          return new builderModel();
      });
      $this->app->singleton('builderAsset', function () {
          return new builderAsset();
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
        config(['auth.providers.users.model' => Http\Models\User::class]);
        //注册Passport
        $this->registerPassport();
        //更改默认上传驱动为public
        config(['filesystems.default' => 'public']);
    }
    public function registerPassport()
    {
        // 添加Passport中间件
        Route::pushMiddlewareToGroup('web', \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class);
        //注册api认证的路由
        Passport::routes();
        //修改auth api 驱动
        config(['auth.guards.api.driver' => 'passport']);
    }
    /**
     * 注册引用服务
     */
    public function registerProviders()
    {
        if ($this->isInstalled()) {
            $package = new Package();
            //合并core配置的服务器提供者和模块的服务提供者
            $providers = array_merge(config('core.providers'),$package->providers());
        }else{
            $providers = config('core.providers');
        }
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
    //注册中间件
    public function middleware()
    {
        //注册跨域控制中间件
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
                  ->pushMiddleware(Http\Middleware\Cors::class);
    }
    /**
     * Get application installation status.
     *
     * @return bool
     */
    public function isInstalled()
    {
        if (!file_exists(storage_path() . DIRECTORY_SEPARATOR . 'installed')) {
            return false;
        }else{
            return true;
        }
    }
}
