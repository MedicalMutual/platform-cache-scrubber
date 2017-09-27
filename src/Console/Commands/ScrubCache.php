<?php namespace Mmic\CacheScrubber\Console\Commands;


use Storage;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScrubCache extends Command {

/**
 * The console command name.
 *
 * @var string
 */
protected $signature = 'cache:scrub';

/**
 * The console command description.
 *
 * @var string
 */
protected $description = 'Deletes all caches known to Platform (application, sessions, views, assets, and media)';

protected $cachePaths = [];

/**
 * Create a new command instance.
 *
 * @return void
 */
public function __construct()
{
	parent::__construct();
}

/**
 * Execute the console command.
 *
 * @return mixed
 */
public function fire()
{
	$this->scrubCaches();
}

public function scrubCaches()
{
	$this->setCachePaths();
	
	$pathValidationResults = $this->getRealPaths($this->cachePaths);
	
	if (!empty($pathValidationResults['invalid'])) {
		$this->generatePathValidationFailureMessage($pathValidationResults);
		
		return false;
	}
	
	$filesystems = [];
	
	foreach ($this->cachePaths as $diskName => $properties) {
		$filesystems[$diskName] = Storage::disk($diskName);
	}
	
	foreach ($filesystems as $diskName => $filesystem) {
		$this->info('Deleting files in "' . $filesystem->getDriver()->getAdapter()->getPathPrefix() . '"...');
		
		//Delete any files.
		
		$files = $filesystem->allFiles();
		
		foreach ($files as $file) {
			if ($file !== '.gitignore' && $file !== '.gitkeep') {
				$filesystem->delete($file);
			}
		}
		
		//Delete any directories (now that they should all be empty).
		
		$dirs = $filesystem->allDirectories();
		
		foreach ($dirs as $dir) {
			$filesystem->deleteDirectory($dir);
		}
	}
}

public function setCachePaths()
{
	$this->cachePaths = config('mmic.cache-scrubber.paths');
}

public function getRealPath(string $path)
{
	//Check if the supplied path is a directory. This is required to mitigate
	//the potential for an invalid path to be specified in the config and later
	//passed through realpath(), which would return a valid path that
	//represents the current directory. Deleting everything inside said
	//directory could have catastrophic consequences!
	
	if (!empty($path) && is_dir($path)) {
		return realpath($path);
	}
	else {
		return false;
	}
}

public function getRealPaths($paths)
{
	$results = ['valid' => [], 'invalid' => []];
	
	foreach ($paths as $name => $properties) {
		$realPath = $this->getRealPath($properties['root']);
		
		if ($realPath !== false) {
			$properties['root'] = $realPath;
			
			$results['valid'][$name] = $properties;
		}
		else {
			$results['invalid'][$name] = $properties;
		}
	}
	
	return $results;
}

public function generatePathValidationFailureMessage($pathValidationResults)
{
	$this->error('One or more invalid paths is specified in the configuration (ensure that each path exists and is writable):');
	
	$i = 1;
	
	foreach ($pathValidationResults['invalid'] as $name => $properties) {
		$this->error($i . '.) "' . $name . '" -> "' . $properties['root'] . '"');
		
		$i++;
	}
}

}
