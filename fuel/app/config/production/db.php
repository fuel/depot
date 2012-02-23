<?php
/**
 * The production database settings.
 */

return array(
	'default' => array(
		'connection'  => array(
			'dsn'        => 'mysql:host='.$_SERVER['FUEL_DBHOST'].';dbname='.$_SERVER['FUEL_DBNAME'],
			'username'   => $_SERVER['FUEL_DBUSER'],
			'password'   => $_SERVER['FUEL_DBPASS'],
		),
	),
);
