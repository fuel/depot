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

class Model_Class extends \Orm\Model
{
	protected static $_table_name = 'docblox_classes';

	protected static $_properties = array(
		'id',
		'docblox_id',
		'name',
		'fullname',
		'namespace',
		'extends',
		'abstract',
		'final',
		'package',
		'docblock' => array(
			'data_type' => 'serialize',
		),
		'properties' => array(
			'data_type' => 'serialize',
		),
	);

	protected static $_belongs_to = array(
		'docblox',
	);

	protected static $_has_many = array(
		'methods' => array(
			'model_to' => '\\Api\\Model_Function',
			'key_to' => 'parent_id',
			'cascade_delete' => true,
		),
		'constants' => array(
			'model_to' => '\\Api\\Model_Constant',
			'key_to' => 'parent_id',
			'cascade_delete' => true,
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
