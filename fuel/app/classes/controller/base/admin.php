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

class Controller_Base_Admin extends Controller_Base_Template
{
	/**
	* @var string page template for admin controllers
	*/
	public $template = 'templates/admin';

	/**
	 * @param   none
	 * @throws  none
	 * @returns	void
	 */
	public function before()
	{
		$result = array();

		// users need to be logged in to access this controller
		if ( ! \Auth::check())
		{
			$result = array(
				'message' => 'You need to be logged in to access that page.',
				'url' => '/users/login',
			);
		}

		elseif ( ! Auth::has_access('access.staff'))
		{
			$result = array(
				'message' => 'Access denied. You need to be a member of staff to access that page.',
				'url' => '/',
			);
		}

		elseif ( ! Auth::has_access('access.admin'))
		{
			$result = array(
				'message' => 'Access denied. You need to be an administrator to access that page.',
				'url' => '/',
			);
		}

		if ( ! empty($result))
		{
			if (\Input::is_ajax())
			{
				$this->response(array($result['message']), 403);
			}
			else
			{
				\Messages::error($result['message']);
				\Response::redirect($result['url']);
			}
		}

		parent::before();
	}
}
