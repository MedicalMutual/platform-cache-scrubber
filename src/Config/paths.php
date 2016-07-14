<?php

return [
	
	'assets' => [
		
		'driver' => 'local',
		'root' => realpath(public_path('cache/assets')),
		
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
