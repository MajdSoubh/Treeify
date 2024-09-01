<?php

namespace MS\Treeify\Providers;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use MS\Treeify\Treeify;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'self-tree');
        // $this->loadViewsFrom(__DIR__ . '/../views', 'self-tree');
        // // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');

        if ($this->app->runningInConsole())
        {

            // $this->publishes([
            //     __DIR__ . '/../config/config.php' => config_path('self-tree.php'),
            // ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/self-tree'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/self-tree'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/self-tree'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {

        Collection::macro('asTree', function ($onlyParents = null, $parentFieldName = null)
        {

            $result = Treeify::treeify($this, $onlyParents, $parentFieldName);

            return $result;
        });
    }
}
