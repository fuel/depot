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

namespace Documentation;

class Model_Doc extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'page_id',
		'user_id',
		'content',
		'created_at',
	);

	protected static $_belongs_to = array(
		'page' => array(
			'model_to' => '\\Documentation\\Model_Page',
		),
		'user' => array(
			'model_to' => '\\Users\\Model_User',
		),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
	);

	public static function _init()
	{
		// make sure the required modules are loaded
		\Module::load('documentation');
		\Module::load('users');
	}
}
