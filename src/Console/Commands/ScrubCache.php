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

}
