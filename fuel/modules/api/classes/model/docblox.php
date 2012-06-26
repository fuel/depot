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

namespace Api;

class Model_Docblox extends \Orm\Model
{
	protected static $_table_name = 'docblox';

	protected static $_properties = array(
		'id',
		'version_id',
		'package',
		'hash',
		'file',
		'docblock' => array(
			'data_type' => 'serialize',
		),
		'markers' => array(
			'data_type' => 'serialize',
		),
	);

	protected static $_belongs_to = array(
		'version' => array(
			'model_to' => '\\Documentation\\Model_Version',
		),
	);

	protected static $_has_many = array(
		'class' => array(
			'cascade_delete' => true,
		),
		'function' => array(
			'key_to' => 'parent_id',
			'cascade_delete' => true,
		),
		'constant' => array(
			'key_to' => 'parent_id',
			'cascade_delete' => true,
		),
	);

	protected static $_observers = array(
		'Orm\\Observer_Typing' => array(
			'after_load'
		)
	);

	public static function _init()
	{
		// make sure the required modules are loaded
		\Module::load('documentation');
	}

	public static function validate($factory)
	{
		$val = \Validation::forge($factory);

		return $val;
	}

}
