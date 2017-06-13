<?php

namespace CoreCMF\core;

use Illuminate\Support\ServiceProvider;
use CoreCMF\core\Builder\Html as builderHtml;
use CoreCMF\core\Builder\Form as builderForm;
use CoreCMF\core\Builder\Table as builderTable;

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
