<?php

namespace CoreCMF\Core;

use Route;
use Laravel\Passport\Passport;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use CoreCMF\Core\Support\Builder\Main as builderMain;
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
use CoreCMF\Core\App\Models\Package;

class CoreServiceProvider extends ServiceProvider
{
    protected $commands = [
        \CoreCMF\Core\App\Console\InstallCommand::class,
        \CoreCMF\Core\App\Console\UninstallCommand::class,
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
            __DIR__.'/../resources/js/' => public_path('js'),
            __DIR__.'/../resources/css/' => public_path('css'),
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

        $this->app->bind('builderMain', function () {
            return new builderMain();
        });
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
        $this->initConfig();
        //注册providers服务
        $this->registerProviders();
        // 注册门面
        $this->registerAliases();
        //注册Passport
        $this->registerPassport();
        $this->registerMiddleware();
    }
    /**
     * [initConfig 初始化常用配置]
     * @return [type] [description]
     */
    public function initConfig()
    {
        //设置user模型位置
        config(['auth.providers.users.model' => App\Models\User::class]);
        //修改auth api 驱动
        config(['auth.guards.api.driver' => 'passport']);
        //更改默认上传驱动为public
        config(['filesystems.default' => 'public']);
        //队列默认驱动
        config(['queue.default' => 'database']);
        config(['queue.connections.database.table' => 'core_jobs']);
    }
    public function registerPassport()
    {
        Passport::routes();//注册api认证的路由
        Passport::tokensExpireIn(now()->addDays(7)); //默认七天
        Passport::refreshTokensExpireIn(now()->addDays(30));//默认三十天
    }
    /**
     * [registerMiddleware 注册中间件]
     * @return   [type]         [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-03-11
     */
    public function registerMiddleware()
    {
        //Session中间件
        Route::pushMiddlewareToGroup('api', \Illuminate\Session\Middleware\StartSession::class);
    }
    /**
     * 注册引用服务
     */
    public function registerProviders()
    {
        if ($this->isInstalled()) {
            $package = new Package();
            //合并core配置的服务器提供者和模块的服务提供者
            $providers = array_merge(config('core.providers'), $package->providers());
        } else {
            $providers = config('core.providers');
        }
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
    /**
     * [registerAliases 注册门面]
     * @return   [type]         [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-01-29
     */
    public function registerAliases()
    {
        if ($this->isInstalled()) {
            $package = new Package();
            $aliases = $package->aliases();
            $loader = AliasLoader::getInstance();
            foreach ($aliases as $key => $alias) {
                $loader->alias($key, $alias);
            }
        }
    }
    //注册中间件
    public function middleware()
    {
        //注册跨域控制中间件
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
                  ->pushMiddleware(App\Http\Middleware\Cors::class);
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
        } else {
            return true;
        }
    }
}
