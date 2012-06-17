<?php

namespace Fuel\Migrations;

class Version_Editable
{
	function up()
	{
		// add the editable field
		\DBUtil::add_fields('versions', array(
			'editable' => array('type' => 'tinyint', 'constraint' => 1, 'default' => 0),
		));

		\DB::query('UPDATE `pages` SET `editable` = 0')->execute();
	}

	function down()
	{
		// drop the editable field
		\DBUtil::drop_fields('versions', 'editable');
	}

}
