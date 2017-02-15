<?php namespace Nikapps\BazaarApiLaravel;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Nikapps\BazaarApiLaravel\Console\BazaarApiClearCacheCommand;
use Nikapps\BazaarApiLaravel\Console\BazaarApiRefreshTokenCommand;

class BazaarApiLaravelServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('nikapps/bazaar-api-laravel');

        AliasLoader::getInstance()->alias(
            'BazaarApi',
            'Nikapps\BazaarApiLaravel\BazaarApiFacade'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(['refresh-token']);
        $this->commands(['clear-cache']);
        $this->registerCommands();

        $this->app->bind('BazaarApi', function ($app) {
            $config = $app['config'];
            return new BazaarApiFactory($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    public function registerCommands()
    {

        $this->app['refresh-token'] = $this->app->share(function ($app) {
            $config = $app['config'];
            return new BazaarApiRefreshTokenCommand($config);
        });

        $this->app['clear-cache'] = $this->app->share(function ($app) {
            $config = $app['config'];
            return new BazaarApiClearCacheCommand($config);
        });
    }
}
