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

class Controller_Reset extends \Controller_Base_Public
{
	/**
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// display the reset password page
		$this->template->content = \View::forge('reset/index');
	}

}
