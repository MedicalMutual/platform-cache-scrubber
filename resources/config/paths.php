<?php

return [
	
	'assets' => [
		
		'driver' => 'local',
		'root' => public_path('cache/assets'),
		
	],
	
	'cache' => [
		
		'driver' => 'local',
		'root' => storage_path('framework/cache'),
		
	],
	
	'sessions' => [
		
		'driver' => 'local',
		'root' => storage_path('framework/sessions'),
		
	],
	
	'views' => [
		
		'driver' => 'local',
		'root' => storage_path('framework/views'),
		
	],
	
];
