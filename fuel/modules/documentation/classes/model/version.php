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
		'created_at',
	);

	protected static $_has_many = array(
		'docblox' => array(
			'model_to' => '\\Api\\Model_Docblox',
			'cascade_delete' => true
		),
		'page' => array(
			'model_to' => '\\Documentation\\Model_Page',
			'cascade_delete' => true
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
		\Module::load('api');
		\Module::load('documentation');
	}

	public static function validate($forge)
	{
		$val = \Validation::forge($forge);

		$val->add_callable('\\Admin\\Model_Version')
			->set_message('valid_path', 'The field :label does not contain an accessible path.');

		$val->add_field('major', 'Major', 'required|is_numeric|numeric_min[0]');
		$val->add_field('minor', 'Minor', 'required|is_numeric|numeric_min[0]');
		$val->add_field('branch', 'Branch name', 'required|max_length[32]');
		$val->add_field('default', 'Default', 'required|is_numeric|numeric_min[0]|numeric_max[1]');
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
