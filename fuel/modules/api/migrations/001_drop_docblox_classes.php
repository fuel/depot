<?php

namespace Fuel\Migrations;

class Drop_Docblox_Classes
{
	function up()
	{
		// drop the classes field
		\DBUtil::drop_fields('docblox', 'classes');
		\DBUtil::drop_fields('docblox', 'functions');
		\DBUtil::drop_fields('docblox', 'constants');
	}

	function down()
	{
		// add the classes field
		\DBUtil::add_fields('docblox', array(
			'classes' => array('type' => 'longtext'),
			'functions' => array('type' => 'longtext'),
			'constants' => array('type' => 'longtext'),
		));
	}

}
