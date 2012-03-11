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

class Model_Docblox extends \Orm\Model
{
	protected static $_table_name = 'docblox';

	protected static $_properties = array(
		'id',
		'version_id',
		'package',
		'hash',
		'file',
		'docblock',
		'markers',
		'functions',
		'classes',
		'constants',
	);

	protected static $_belongs_to = array(
		'version'
	);

	protected static $_observers = array(
	);

	public static function validate($factory)
	{
		$val = \Validation::forge($factory);

		return $val;
	}

}
