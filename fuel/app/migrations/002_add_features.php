<?php

namespace Fuel\Migrations;

class Add_Features
{

	function up()
	{
		// increase the version branch length to 32 characters
		\DBUtil::modify_fields('versions', array(
			'branch' => array('constraint' => 32, 'type' => 'varchar'),
		));
	}


	function down()
	{
		// reduce the version branch length back to 10 characters
		\DBUtil::modify_fields('versions', array(
			'branch' => array('constraint' => 10, 'type' => 'varchar'),
		));
	}

}
