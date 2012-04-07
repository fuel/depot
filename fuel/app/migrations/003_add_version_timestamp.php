<?php

namespace Fuel\Migrations;

class Add_Version_Timestamp
{

	function up()
	{
		// add the created_at field
		\DBUtil::add_fields('versions', array(
			'created_at' => array('type' => 'int'),
		));

		// and give the field a default value
		$time = time();

		\DB::query('UPDATE `versions` SET `created_at` = '.$time.' + `id`')->execute();
	}


	function down()
	{
		// drop the created_at field
		\DBUtil::drop_fields('versions', 'created_at');
	}
}
