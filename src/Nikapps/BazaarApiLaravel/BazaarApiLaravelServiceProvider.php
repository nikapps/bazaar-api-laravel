<?php namespace Nikapps\BazaarApiLaravel;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class BazaarApiLaravelServiceProvider extends ServiceProvider {

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
            'Bazaar',
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
        $this->registerCommands();

        $this->app->bind('Bazaar', function(){

            return new BazaarApi();
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

    public function registerCommands(){
        $this->app['refresh-token'] = $this->app->share(function($app)
        {
            return new BazaarApiRefreshTokenCommand();
        });


    }

}
