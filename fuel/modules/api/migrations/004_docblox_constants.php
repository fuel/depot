<?php

namespace Fuel\Migrations;

class Docblox_Constants
{
	function up()
	{
		// create the docblox classes table
		\DBUtil::create_table('docblox_constants', array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'parent_id' => array('type' => 'int', 'constraint' => 11),
			'name' => array('type' => 'varchar', 'constraint' => 100),
			'type' => array('type' => 'varchar', 'constraint' => 25),
			'value' => array('type' => 'text'),
			'namespace' => array('type' => 'varchar', 'constraint' => 255),
			'package' => array('type' => 'varchar', 'constraint' => 100),
			'docblock' => array('type' => 'longtext'),
		), array('id'));
	}

	function down()
	{
		// drop the constants table
		\DBUtil::drop_table('docblox_constants');
	}

}
