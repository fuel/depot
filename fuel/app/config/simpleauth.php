<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

return array(

	/**
	 * DB table name for the user table
	 */
	'table_name' => 'users',

	/**
	 * This will allow you to use the group & acl driver for non-logged in users
	 */
	'guest_login' => true,

	/**
	 * Groups as id => array(name => <string>, roles => <array>)
	 */
	'groups' => array(
		-1   => array('name' => 'Banned',         'roles' => array('banned')),
		 0   => array('name' => 'Guests',         'roles' => array()),
		 1   => array('name' => 'Users',          'roles' => array('user')),
		 50  => array('name' => 'Staff',          'roles' => array('user', 'staff')),
		 100 => array('name' => 'Administrators', 'roles' => array('user', 'staff', 'admin')),
	),

	/**
	 * Roles as name => array(location => rights)
	 */
	'roles' => array(
		// default visitor rights
		'#'           => array(
			'access' => array('public')
		),

		// defined but banned user rights
		'banned'      => false,

		// site user rights
		'user'        => array(
			'access' => array('user')
		),

		// site staff rights
		'staff'   => array(
			'access' => array('staff')
		),

		// site administrator rights
		'admin'       => array(
			'access' => array('admin')
		),

		// super user rights
		'super'       => true,
	),

	/**
	 * Salt for the login hash
	 */
	'login_hash_salt' => 'YoUrVeRySeCrEtSaLtHeRe',

	/**
	 * $_POST key for login username
	 */
	'username_post_key' => 'username',

	/**
	 * $_POST key for login password
	 */
	'password_post_key' => 'password',
);
