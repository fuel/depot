<?php

namespace Fuel\Migrations;

class Drop_Docblox_Classes
{
	function up()
	{
		// drop the classes field
		\DBUtil::drop_fields('docblox', 'classes');
	}

	function down()
	{
		// add the classes field
		\DBUtil::add_fields('docblox', array(
			'classes' => array('type' => 'longtext'),
		));
	}

}
