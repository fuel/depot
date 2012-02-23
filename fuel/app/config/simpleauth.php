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
		 50  => array('name' => 'Staff',          'roles' => array('staff')),
		 100 => array('name' => 'Administrators', 'roles' => array('user', 'staff', 'admin')),
	),

	/**
	 * Roles as name => array(location => rights)
	 */
	'roles' => array(
		// default visitor rights
		'#'           => array(
			'website' => array('read')
		),

		// defined but banned user rights
		'banned'      => false,

		// normal user rights
		'user'        => array(
		),

		// site staff rights
		'staff'   => array(
		),

		// site administrator rights
		'admin'       => array(
			'admin'   => array('create', 'read', 'update', 'delete'),
		),

		// super user rights
		'super'       => true,
	),

	/**
	 * Salt for the login hash
	 */
	'login_hash_salt' => 'MA*&##*(*^@#BO*&O#NYTF',

	/**
	 * $_POST key for login username
	 */
	'username_post_key' => 'username',

	/**
	 * $_POST key for login password
	 */
	'password_post_key' => 'password',
);
