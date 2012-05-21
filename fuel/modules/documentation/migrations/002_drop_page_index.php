<?php

namespace Fuel\Migrations;

class Drop_Page_Index
{
	function up()
	{
		// recreate the page indexes
		\DBUtil::drop_index('pages', 'left_id');
		\DBUtil::drop_index('pages', 'right_id');
	}

	function down()
	{
		// recreate the page indexes
		\DBUtil::create_index('pages', array('version_id', 'left_id'), 'left_id', 'UNIQUE');
		\DBUtil::create_index('pages', array('version_id', 'right_id'), 'right_id', 'UNIQUE');
	}

}
