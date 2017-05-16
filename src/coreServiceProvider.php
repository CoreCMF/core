<?php

namespace CoreCMF\core;

use Illuminate\Support\ServiceProvider;
use CoreCMF\core\Builder\Main;

class coreServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('builderMain', function () {
            return new Main();
        });
    }
}