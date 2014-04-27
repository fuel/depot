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

class Controller_Admin_Dashboard extends \Admin\Controller_Base
{
	/**
	 * action preparation method
	 */
	public function before()
	{
		// this controller only serves HMVC calls
		if ( ! \Request::is_hmvc())
		{
			throw new \HttpNotFoundException();
		}

		// call the base controllers before method
		parent::before();
	}


	/**
	 * capture all calls to this controller
	 */
	public function router($resource, array $arguments)
	{
		// user account information
		$data = array();

		// api docs data
		$data['versions'] = Model_Version::query()->order_by('major', 'ASC')->order_by('minor', 'ASC')->order_by('branch', 'ASC')->get();

		// page counts
		$data['pagecounts'] = array();
		foreach ($data['versions'] as $version)
		{
			$data['pagecounts'][$version->id] = count($version->page);
		}

		// docblox counts
		\Module::load('api');
		$data['apicounts'] = array();
		foreach ($data['versions'] as $version)
		{
			$data['apicounts'][$version->id] = \Api\Model_Docblox::query()->where('version_id', $version->id)->count();
		}

		// return the dashboard view
		return \Theme::instance()->view('documentation/admin/dashboard')->set($data);
	}
}
