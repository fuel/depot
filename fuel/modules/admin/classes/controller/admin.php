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

class Controller_Admin extends \Controller_Base_Admin
{
	/**
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// by default, the base controller loads the subpage template
		// this will adds content to the body of the template
		$this->template->content = \View::forge('admin/index');
	}

}
