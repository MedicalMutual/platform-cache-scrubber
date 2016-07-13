<?php namespace Mmic\CacheScrubber\Providers;


use App\Providers;
use Illuminate\Support\ServiceProvider;

class CacheScrubberServiceProvider extends ServiceProvider {

/**
 * {@inheritDoc}
 */
public function boot()
{
	parent::boot();
}

/**
 * {@inheritDoc}
 */
public function register()
{
	$this->configureExtension();
	
	$this->registerConsoleCommands();
}

protected function configureExtension()
{
	//Publish config file(s) and merge as necessary.
	
	$file = 'mmic.cache-scrubber.paths.php';
	
	$this->publishes([
		realpath(__DIR__.'/../Config/paths.php') => config_path($file),
	]);
	
	$configFile = config_path() . '/' . $file;
	
	if (file_exists($configFile)) {
		$this->mergeConfigFrom(
			$configFile, 'filesystems.disks'
		);
	}
}

protected function registerConsoleCommands()
{
	$this->commands('Mmic\CacheScrubber\Console\Commands\ScrubCache');
}

}
