# Cache Scrubber Extension for Cartalyst Platform #

Modern frameworks utilize caching extensively, which can lead to unexpected behavior during routine development activities.

Laravel includes an Artisan command that clears the "application cache", but there are several other caches, some of which are specific to Laravel, and some of which are specific to Platform, that are not cleared.

This simple extension adds a new Artisan command, `cache:scrub`, which empties *all* caches known to Platform.

## Installation ##

Run the following terminal command, from within the project root:

```
composer require mmic/platform-cache-scrubber
```

Next, log into Platform, navigate to Operations -> Extensions, locate the `Cache Scrubber` extension, and click `Install` (at top right), and then click `Enable` (also at top right).

Finally, publish the configuration file:

```
php artisan vendor:publish
```

The configuration file is published to the following location, relative to the project root:

`config/mmic.cache-scrubber.paths.php`

## Usage ##

To clear all caches, simply issue the following command on the terminal, from within the project root:

```
php artisan cache:scrub
```

## Configuration ##

Adding a new/custom cache is as easy as editing the configuration file, `config/mmic.cache-scrubber.paths.php`.

All of Laravel and Platform's caches are configured to be cleared, by default:

    <?php

    return [
        
        'assets' => [
            
            'driver' => 'local',
            'root' => realpath(public_path('cache/assets')),
            
        ],
        
        'media' => [
            
            'driver' => 'local',
            'root' => realpath(public_path('cache/media')),
            
        ],
        
        'cache' => [
            
            'driver' => 'local',
            'root' => realpath(storage_path('framework/cache')),
            
        ],
        
        'sessions' => [
            
            'driver' => 'local',
            'root' => realpath(storage_path('framework/sessions')),
            
        ],
        
        'views' => [
            
            'driver' => 'local',
            'root' => realpath(storage_path('framework/views')),
            
        ],
        
    ];

Simply add a new element that defines the driver and filesystem path to this array, and the Cache Scrubber will empty the directory when called.

## Misc. Notes ##

As of this writing, `.gitignore` and `.gitkeep` files are *not* deleted when caches are cleared. In the near future, a more robust "mask" will be added that allows for further customization in this regard.

## Contributing ##

Feel free to submit Pull Requests!