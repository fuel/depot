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

class Model_Function extends \Orm\Model
{
	protected static $_table_name = 'docblox_functions';

	protected static $_properties = array(
		'id',
		'parent_id',
		'name',
		'type',
		'namespace',
		'package',
		'final',
		'abstract',
		'static',
		'visibility',
		'docblock' => array(
			'data_type' => 'serialize',
		),
		'arguments' => array(
			'data_type' => 'serialize',
		),
	);

	protected static $_belongs_to = array(
		'docblox' => array(
			'model_to' => '\\Api\\Model_Docblox',
			'key_from' => 'parent_id',
		),
		'classes' => array(
			'model_to' => '\\Api\\Model_Class',
			'key_from' => 'parent_id',
		),
	);

	protected static $_observers = array(
		'Orm\\Observer_Typing' => array(
			'after_load'
		)
	);

	public static function validate($factory)
	{
		$val = \Validation::forge($factory);

		return $val;
	}

}
