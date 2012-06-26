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

class Controller_Hmvc extends \Controller
{
	/**
	 * before action processing.
	 */
	public function before()
	{
		// this controller only serves HMVC calls
		if ( ! \Request::is_hmvc())
		{
			throw new \HttpNotFoundException();
		}
	}


	public function action_versioncheck($major = null, $minor = null, $branch = null)
	{
		if ($major === null or $minor === null or $branch === null)
		{
			throw new \Exception('Documentation Versioncheck: incorrect URI parameters specified.');
		}

		// check if we know this version
		$result = \DB::select()->from('versions')->where('major', $major)->where('minor', $minor)->where('branch', $branch)->execute();

		if ( ! $result->count())
		{
			throw new \Exception('Documentation Versioncheck: requested version does not exist.');
		}

		// return the record's id
		return \Response::forge($result[0]['id']);
	}

	public function action_versioncreate($major = null, $minor = null, $branch = null)
	{
		if ($major === null or $minor === null or $branch === null)
		{
			throw new \Exception('Documentation Versioncheck: incorrect URI parameters specified.');
		}

		// check if we know this version
		$result = \DB::select()->from('versions')->where('major', $major)->where('minor', $minor)->where('branch', $branch)->execute();

		if ($result->count())
		{
			throw new \Exception('Documentation Versioncheck: requested version already exists.');
		}

		list($result, $rows_affected) = \DB::insert('versions')->set(array(
			'major' => $major,
			'minor' => $minor,
			'branch' => $branch,
			'default' => 0,
			'editable' => 0,
			'codepath' => '',
			'docspath' => '',
			'docbloxpath' => '',
		))->execute();

		// return the record's id
		return \Response::forge($result);
	}
}
