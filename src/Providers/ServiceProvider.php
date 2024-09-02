<?php

namespace MS\Treeify\Providers;

use Illuminate\Support\Collection;
use MS\Treeify\Treeify;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        Collection::macro('treeify', function ($onlyParents = null, $parentFieldName = null)
        {
            $result = Treeify::treeify($this, $onlyParents, $parentFieldName);
            return $result;
        });
    }
}
