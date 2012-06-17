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

class Model_Constant extends \Orm\Model
{
	protected static $_table_name = 'docblox_constants';

	protected static $_properties = array(
		'id',
		'parent_id',
		'name',
		'type',
		'value',
		'namespace',
		'package',
		'docblock',
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
	);

	public static function validate($factory)
	{
		$val = \Validation::forge($factory);

		return $val;
	}

}
