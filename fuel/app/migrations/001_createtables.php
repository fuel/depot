<?php

namespace Fuel\Migrations;

class Createtables
{

	function up()
	{
		// create Fuel Depot tables

		// table versions
		\DBUtil::create_table('versions', array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'major' => array('type' => 'tinyint', 'constraint' => 2),
			'minor' => array('type' => 'tinyint', 'constraint' => 2),
			'branch' => array('type' => 'varchar', 'constraint' => 10),
			'default' => array('type' => 'tinyint', 'constraint' => 1, 'default' => 0),
			'codepath' => array('type' => 'varchar', 'constraint' => 100),
			'docspath' => array('type' => 'varchar', 'constraint' => 100),
			'docbloxpath' => array('type' => 'varchar', 'constraint' => 100),
		), array('id'));

		// table docblox
		\DBUtil::create_table('docblox', array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'version_id' => array('type' => 'int', 'constraint' => 11),
			'package' => array('type' => 'varchar', 'constraint' => 100),
			'hash' => array('type' => 'char', 'constraint' => 32),
			'file' => array('type' => 'varchar', 'constraint' => 200),
			'docblock' => array('type' => 'longtext'),
			'markers' => array('type' => 'longtext'),
			'functions' => array('type' => 'longtext'),
			'classes' => array('type' => 'longtext'),
			'constants' => array('type' => 'longtext'),
		), array('id'));

		// create FuelPHP system tables

		// table sessions
		\DBUtil::create_table('sessions', array(
			'session_id' => array('type' => 'varchar', 'constraint' => 40),
			'previous_id' => array('type' => 'varchar', 'constraint' => 40),
			'user_agent' => array('type' => 'text'),
			'ip_hash' => array('type' => 'char', 'constraint' => 32),
			'created' => array('type' => 'int', 'constraint' => 10, 'unsigned' => true),
			'updated' => array('type' => 'int', 'constraint' => 10, 'unsigned' => true),
			'payload' => array('type' => 'longtext'),
		), array('session_id'));
		\DBUtil::create_index('sessions', 'previous_id', 'previous_id', 'UNIQUE');

		// table users
		\DBUtil::create_table('users', array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'username' => array('type' => 'varchar', 'constraint' => 50),
			'password' => array('type' => 'varchar', 'constraint' => 255),
			'group' => array('type' => 'int', 'constraint' => 11, 'default' => 1),
			'email' => array('type' => 'varchar', 'constraint' => 255),
			'last_login' => array('type' => 'varchar', 'constraint' => 25),
			'login_hash' => array('type' => 'varchar', 'constraint' => 255),
			'profile_fields' => array('type' => 'text'),
			'created_at' => array('type' => 'int', 'constraint' => 10, 'unsigned' => true),
		), array('id'));
		\DBUtil::create_index('users', array('username', 'email'), 'username', 'UNIQUE');

		// default data
		\Auth::instance()->create_user('admin', 'password', 'depot.master@fuelphp.com', 100);

		\DB::query("INSERT INTO `versions` (`id`, `major`, `minor`, `branch`, `default`, `codepath`, `docspath`, `docbloxpath`) VALUES (1, 1, 1, 'develop', 1, '', '', '/data/www/mvhosts/fuel.catwoman.exite.local/docblox');");
	}


	function down()
	{
		// drop Fuel Depot tables
		\DBUtil::drop_table('versions');
		\DBUtil::drop_table('docblox');

		// drop FuelPHP system tables
		\DBUtil::drop_table('sessions');
		\DBUtil::drop_table('users');
	}

}
