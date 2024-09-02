<?php

namespace Maso\Treeify\Providers;

use Illuminate\Support\Collection;
use Maso\Treeify\Treeify;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        Collection::macro('treeify', function ($onlyParents = null, $parentFieldName = null)
        {
            $result = Treeify::treeify($this, $onlyParents, $parentFieldName);
            return $result;
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
