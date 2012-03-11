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

/**
 * Administration dashboard
 */
class Controller_Admin extends Controller_Base
{
	/**
	 * The index action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
		// user account information
		$data['active_users'] = Model_User::find()->where('group', '!=', -1)->count();
		$data['banned_users'] = Model_User::find()->where('group', '=', -1)->count();
		$data['github_accounts'] = Model_Authentication::find()->where('provider', '=', 'github')->count();
		$data['twitter_accounts'] = Model_Authentication::find()->where('provider', '=', 'twitter')->count();

		// api docs data
		$data['versions'] = Model_Version::find()->order_by('major', 'ASC')->order_by('minor', 'ASC')->order_by('branch', 'ASC')->related('docblox')->get();

		$this->template->title = 'Dashboard';
		$this->template->content = \View::forge('dashboard', $data);
	}

}

/* End of file admin.php */
