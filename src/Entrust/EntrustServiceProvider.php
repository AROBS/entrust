<?php namespace Zizaco\Entrust;

use Illuminate\Support\ServiceProvider;

class EntrustServiceProvider extends ServiceProvider
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
        // Work-around to deal with the removal of [packages] from Laravel 5
        // $this->package('zizaco/entrust', 'entrust', __DIR__.'/../');
        // Is it possible to register the config?
        if (method_exists($this->app['config'], 'package')) {
            $this->app['config']->package('zizaco/entrust',__DIR__ . '/../');
        } else {
            // Load the config for now..
            $publishedConfigFile = base_path()."/config/packages/zizaco/entrust/config.php";
            $publishedConfig = [];
            if(file_exists($publishedConfigFile)) {
                $publishedConfig = $this->app['files']->getRequire($publishedConfigFile);
            }
            $config = $this->app['files']->getRequire(__DIR__ .'/../config/config.php');
            $this->app['config']->set('entrust', array_merge($config, $publishedConfig));

        }

        $this->commands([
            'command.entrust.migration',
            'command.entrust.config'
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEntrust();

        $this->registerCommands();
    }

    /**
	 * Register the application bindings.
	 *
	 * @return void
	 */
	private function registerEntrust()
	{
		$this->app->bind('entrust', function ($app) {
            return new Entrust($app);
        });
	}

	/**
	 * Register the artisan commands.
	 *
	 * @return void
	 */
	private function registerCommands()
	{
        $this->app->bindShared('command.entrust.migration', function ($app) {
            return new MigrationCommand();
        });
        $this->app->bindShared('command.entrust.config', function ($app) {
            return new ConfigCommand();
        });
	}

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'command.entrust.migration',
            'command.entrust.config'
        );
    }
}
