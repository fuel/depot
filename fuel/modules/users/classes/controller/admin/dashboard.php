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

namespace Users;

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

		// load the ninjauth package
		\Package::load('ninjauth');
	}


	/**
	 * capture all calls to this controller
	 */
	public function router($resource, array $arguments)
	{
		// user account information
		$data = array();
		$data['active_users'] = Model_User::find()->where('group', '!=', -1)->count();
		$data['banned_users'] = Model_User::find()->where('group', '=', -1)->count();
		$data['github_accounts'] = Model_Authentication::find()->where('provider', '=', 'github')->count();
		$data['twitter_accounts'] = Model_Authentication::find()->where('provider', '=', 'twitter')->count();
		$data['facebook_accounts'] = Model_Authentication::find()->where('provider', '=', 'facebook')->count();
		$data['google_accounts'] = Model_Authentication::find()->where('provider', '=', 'google')->count();

		// return the dashboard view
		return \Theme::instance()->view('users/admin/dashboard')->set($data);
	}
}
