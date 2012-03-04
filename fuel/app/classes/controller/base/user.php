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

class Controller_Base_User extends Controller_Base_Template
{
	/**
	 * @param   none
	 * @throws  none
	 * @returns	void
	 */
	public function before()
	{
		// users need to be logged in to access this controller
		if ( ! \Auth::check())
		{
			\Messages::error('Access denied. Please login first');
			\Response::redirect('/users/login');
		}

		parent::before();
	}

}
