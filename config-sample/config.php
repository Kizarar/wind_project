<?php

$config = array(
	
	'db' => array(
		'server' => "server.example.org",
		'username' => "youruser",
		'password' => "yourpassword",
		'database' => "yourdatabase"
		),
		
	'site' => array(
		'domain' => 'server.example.org',
		'url' => 'http://server.example.org/'
		),
	
	'cookies' => array(
		'expire' => (60 * 60 * 24 * 30)
		),

	'message' => array(
		'delay' => 5
		),

	'templates' => array(
		'path' => $root_path.'templates/',
		'compiled_path' => $root_path.'templates/_compiled/',
		'default' => 'basic'
		),
		
	'language' => array(
		'default' => 'greek'
		),
		
	'smarty' => array(
		'class' => '/usr/share/php/smarty/libs/Smarty.class.php'
		),
		
	'constructor' => array(
		'max_rows' => 50
		),
	
	'dns' => array(
		'root_zone' => 'yourdomain',
		'ns_zone' => 'ns.yourdomain',
		'reverse_zone' => 'in-addr.arpa',
		'forward_zone_schema' => $root_path.'tools/dnszones-poller/yourdomain.schema',
		'reverse_zone_schema' => $root_path.'tools/dnszones-poller/10.in-addr.arpa.schema'
		),
	
	'folders' => array(
		'photos' => $root_path.'files/photos/'
		),
	
	'mail' => array(
		'smtp' => '', // if not set default used from php.ini file
		'smtp_port' => '25',
		'from' => 'hostmaster@wind.cube.gr',
		'from_name' => 'WiND Hostmaster'
		),
	
	'gmap' => array(
		'server' => 'maps.google.com',
		'keys' => array( // Domains must be as in $_SERVER['SERVER_NAME'] variable
			'server.example.org/path' => 'example_key'
			),
		'bounds' => array(
			'min_latitude' => MINLAT_GPS_COORDINATE,
			'min_longitude' => MINLON_GPS_COORDINATE,
			'max_latitude' => MAXLAT_GPS_COORDINATE,
			'max_longitude' => MAXLON_GPS_COORDINATE
			)
		),
	
	'debug' => array(
		'enabled' => FALSE
		)
		
);

?>