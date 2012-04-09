<?php
/**
 * Part of Fuel Depot.
 *
 * @package    FuelDepot
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2012 Fuel Development Team
 * @link       http://depot.fuelphp.com
 */

namespace Admin;

/*
 * make sure the package is loaded
 */
\Package::load('nestedsets');

class Model_Page extends \Nestedsets\Model
{
	/*
	 * @var	override the default nestedset tree configuration
	 */
	protected static $tree = array(
		'tree_field'     => 'version_id',
		'title_field'    => 'slug',
	);

	protected static $_properties = array(
		'id',
		'version_id',
		'left_id',
		'right_id',
		'symlink_id',
		'editable',
		'user_id',
		'title',
		'slug',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
		'version',
		'user',
	);

	protected static $_has_many = array(
		'latest' => array(
			'key_from' => 'id',
			'model_to' => '\\Admin\\Model_Doc',
			'key_to' => 'page_id',
			'cascade_save' => false,
			'cascade_delete' => false,
			'conditions' => array(
				'order_by' =>array(
					'created_at' => 'DESC',
				),
			),
		),
		'doc' => array('cascade_delete' => true),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);
}
