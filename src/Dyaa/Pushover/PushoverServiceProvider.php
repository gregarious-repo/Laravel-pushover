<?php namespace Dyaa\Pushover;

use Illuminate\Support\ServiceProvider;

class PushoverServiceProvider extends ServiceProvider {

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
		$this->package('dyaa/pushover', 'dyaa_pushover');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app['pushover'] = $this->app->share(function($app)
        {
          return new Pushover($app['config']);
        });

        $this->app->booting(function()
        {
          $loader = \Illuminate\Foundation\AliasLoader::getInstance();
          $loader->alias('Pushover', 'Dyaa\Pushover\Facades\Pushover');
        });

		$this->app['pushover.send'] = $this->app->share(function ()
		{
			return new Commands\PushoverCommand($this->app['pushover']);
		});

		$this->commands(
			'pushover.send'
		);
	}

	/**
	 * Get the services provided by the provider.	
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('pushover');
	}

}