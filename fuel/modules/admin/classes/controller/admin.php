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
		$data['active_users'] = \Users\Model_User::find()->where('group', '!=', -1)->count();
		$data['banned_users'] = \Users\Model_User::find()->where('group', '=', -1)->count();
		$data['github_accounts'] = \Users\Model_Authentication::find()->where('provider', '=', 'github')->count();
		$data['twitter_accounts'] = \Users\Model_Authentication::find()->where('provider', '=', 'twitter')->count();
		$data['facebook_accounts'] = \Users\Model_Authentication::find()->where('provider', '=', 'facebook')->count();
		$data['google_accounts'] = \Users\Model_Authentication::find()->where('provider', '=', 'google')->count();

		// api docs data
		$data['versions'] = Model_Version::find()->order_by('major', 'ASC')->order_by('minor', 'ASC')->order_by('branch', 'ASC')->get();

		// page counts
		\Module::load('documentation');
		$data['pagecounts'] = array();
		foreach ($data['versions'] as $version)
		{
			$data['pagecounts'][$version->id] = \Documentation\Model_Page::query()->where('version_id', $version->id)->count();
		}

		// docblox counts
		\Module::load('api');
		$data['apicounts'] = array();
		foreach ($data['versions'] as $version)
		{
			$data['apicounts'][$version->id] = \Api\Model_Docblox::query()->where('version_id', $version->id)->count();
		}

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Dashboard');

		// and define the content body
		\Theme::instance()->set_partial('content', 'admin/dashboard')->set($data);
	}

}

/* End of file admin.php */
