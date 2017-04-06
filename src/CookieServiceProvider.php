<?php

namespace Skyronic\Cookie;

use Illuminate\Support\ServiceProvider;

class CookieServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    public function boot () {
        if ($this->app->runningInConsole ()) {
            $this->mergeConfigFrom(__DIR__."/config/cookie.php", 'cookie');
            $this->commands([
                BakeListCommand::class,
                BakeInitCommand::class
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
    }
}
