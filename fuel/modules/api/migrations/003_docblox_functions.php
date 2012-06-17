<?php

namespace Fuel\Migrations;

class Docblox_Functions
{
	function up()
	{
		// create the docblox classes table
		\DBUtil::create_table('docblox_functions', array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'parent_id' => array('type' => 'int', 'constraint' => 11),
			'name' => array('type' => 'varchar', 'constraint' => 100),
			'type' => array('type' => 'varchar', 'constraint' => 25),
			'namespace' => array('type' => 'varchar', 'constraint' => 255),
			'package' => array('type' => 'varchar', 'constraint' => 100),
			'final' => array('type' => 'tinyint', 'constraint' => 1, 'default' => 0),
			'abstract' => array('type' => 'tinyint', 'constraint' => 1, 'default' => 0),
			'static' => array('type' => 'tinyint', 'constraint' => 1, 'default' => 0),
			'visibility' => array('type' => 'varchar', 'constraint' => 10, 'default' => 'public'),
			'docblock' => array('type' => 'longtext'),
			'arguments' => array('type' => 'longtext'),
		), array('id'));
	}

	function down()
	{
		// drop the classes table
		\DBUtil::drop_table('docblox_functions');
	}

}
