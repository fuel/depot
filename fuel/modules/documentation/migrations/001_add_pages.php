<?php

namespace Fuel\Migrations;

class Add_Pages
{
	function up()
	{
		// create table pages
		\DBUtil::create_table('pages', array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'version_id' => array('type' => 'int', 'constraint' => 11),
			'left_id' => array('type' => 'int', 'constraint' => 11),
			'right_id' => array('type' => 'int', 'constraint' => 11),
			'symlink_id' => array('type' => 'int', 'constraint' => 11, 'null' => true),
			'user_id' => array('type' => 'int', 'constraint' => 11),
			'default' => array('type' => 'tinyint', 'constraint' => 1, 'default' => 0),
			'editable' => array('type' => 'tinyint', 'constraint' => 1, 'default' => 1),
			'title' => array('type' => 'varchar', 'constraint' => 50),
			'slug' => array('type' => 'varchar', 'constraint' => 50),
			'created_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
			'updated_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
		), array('id'));
		\DBUtil::create_index('pages', 'version_id', 'version_id');
		\DBUtil::create_index('pages', array('version_id', 'left_id'), 'left_id', 'UNIQUE');
		\DBUtil::create_index('pages', array('version_id', 'right_id'), 'right_id', 'UNIQUE');

		// create table docs
		\DBUtil::create_table('docs', array(
			'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
			'page_id' => array('type' => 'int', 'constraint' => 11),
			'user_id' => array('type' => 'int', 'constraint' => 11),
			'content' => array('type' => 'text'),
			'created_at' => array('type' => 'int', 'constraint' => 11, 'default' => 0),
		), array('id'));
	}

	function down()
	{
		// drop table pages
		\DBUtil::drop_table('pages');

		// drop table docs
		\DBUtil::drop_table('docs');
	}

}
