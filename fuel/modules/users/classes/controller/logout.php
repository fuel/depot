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

class Controller_Logout extends \Controller_Base_User
{
	/**
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		\Auth::logout();
		\Session::delete('ninjauth.user');
		\Session::delete('ninjauth.authentication');
		\Messages::success('Logout successful');
		\Response::redirect('/');
	}

}
