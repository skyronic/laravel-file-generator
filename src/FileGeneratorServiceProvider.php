<?php

namespace Skyronic\FileGenerator;

use Illuminate\Support\ServiceProvider;

class FileGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    public function boot () {
        if ($this->app->runningInConsole ()) {
            $this->mergeConfigFrom(__DIR__."/config/filegen.php", 'filegen');
            $this->commands([
                FileGenListCommand::class,
                FileGenNewCommand::class,
                FileGenCommand::class
            ]);
        }

        $this->publishes([
            __DIR__.'/../boilerplates/' => resource_path('boilerplates'),
        ], 'goodies');

        $this->publishes([
            __DIR__.'/config/filegen.php' => config_path('filegen.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
