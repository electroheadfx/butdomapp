<?php
/**
 * The development database settings. These get merged with the global settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host=localhost;dbname=appbutdom_prod',
			'username'   => 'root',
			'password'   => 'lolo',
		),
		'enable_cache'   => true,
	),

	/**
	 * Base Redis config
	 */
	'redis' => array(
		'default' => array(
			'hostname'  => '127.0.0.1',
			'port'      => 6379,
			'timeout'	=> null,
		)
	),
	
);
