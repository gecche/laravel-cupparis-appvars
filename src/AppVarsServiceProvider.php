<?php namespace Gecche\Cupparis\AppVars;

use Illuminate\Support\ServiceProvider;

class AppVarsServiceProvider extends ServiceProvider {


	/**
	 * Register
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton('app_vars', function($app)
        {
            return new AppVarsManager($app['files']);
        });
	}

    /**
     * Booting
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/cupparis-appvars.php' => config_path('cupparis-appvars.php'),
        ], 'public');
    }


    /**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['app_vars'];
	}

}
