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

class Model_Version extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'major',
		'minor',
		'branch',
		'default',
		'codepath',
		'docspath',
		'docbloxpath',
	);

	protected static $_has_many = array(
		'docblox'
	);

	protected static $_observers = array(
	);

	public static function validate($factory)
	{
		$val = \Validation::forge($factory);

		$val->add_callable('\\Admin\\Model_Version')
			->set_message('valid_path', 'The field :label does not contain an accessible path.');

		$val->add_field('major', 'Major', 'required|is_numeric|numeric_min[0]');
		$val->add_field('minor', 'Minor', 'required|is_numeric|numeric_min[0]');
		$val->add_field('branch', 'Branch name', 'required|max_length[32]');
		$val->add_field('codepath', 'Local path to the code repository', 'required|max_length[100]|valid_path');
		$val->add_field('docspath', 'Local path to the docs repository', 'required|max_length[100]|valid_path');
		$val->add_field('docbloxpath', 'Local path to the docblox output', 'required|max_length[100]|valid_path');

		return $val;
	}

	public static function _validation_valid_path($value)
	{
		return is_dir($value);
	}
}
