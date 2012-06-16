<?php

namespace Fuel\Migrations;

class Docblox_Classes
{
	function up()
	{
		// create the docblox classes table
		\DBUtil::create_table('docblox_classes', array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'docblox_id' => array('type' => 'int', 'constraint' => 11),
			'abstract' => array('type' => 'tinyint', 'constraint' => 1),
			'final' => array('type' => 'tinyint', 'constraint' => 1),
			'line' => array('type' => 'int', 'constraint' => 11),
			'package' => array('type' => 'varchar', 'constraint' => 100),
			'name' => array('type' => 'varchar', 'constraint' => 100),
			'fullname' => array('type' => 'varchar', 'constraint' => 255),
			'namespace' => array('type' => 'varchar', 'constraint' => 255),
			'extends' => array('type' => 'varchar', 'constraint' => 255),
			'docblock' => array('type' => 'longtext'),
			'properties' => array('type' => 'longtext'),
			'methods' => array('type' => 'longtext'),
			'constants' => array('type' => 'longtext'),
		), array('id'));
	}

	function down()
	{
		// drop the classes table
		\DBUtil::drop_table('docblox_classes');
	}

}
