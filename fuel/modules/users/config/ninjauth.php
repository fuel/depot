<?php
/**
 * Configuration for NinjAuth
 */
return array(

	'urls' => array(
		'registration' => 'users/register',
		'login' => 'users/login',
		'callback' => \Uri::create('users/login/callback'),
		'registered' => 'users/profile',
		'logged_in' => '/',
	),

	/**
	 * Providers
	 *
	 * Providers such as Facebook, Twitter, etc all use different Strategies such as oAuth, oAuth2, etc.
	 * oAuth takes a key and a secret, oAuth2 takes a (client) id and a secret, optionally a scope.
	 *
	 * this is the config template, providers need to be defined in app/config/ninjauth.php !!!
	 */
	'providers' => array(

		'facebook' => array(
			'id' => '',
			'secret' => '',
			'scope' => 'email,offline_access',
		),

		'twitter' => array(
			'key' => '',
			'secret' => '',
		),

		'github' => array(
			'id' => '',
			'secret' => '',
			'scope' => '',
		),

		'google' => array(
			'key' => '',
			'secret' => '',
		),

		'googleplus' => array(
			'key' => '',
			'secret' => '',
		),

	),

	/**
	 * link_multiple_providers
	 *
	 * Can multiple providers be attached to one user account
	 */
	'link_multiple_providers' => true,

	/**
	 * default_group
	 *
	 * How should users be signed up
	 */
	'default_group' => 1,
);
