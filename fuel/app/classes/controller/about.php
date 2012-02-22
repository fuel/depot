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

class Controller_About extends \Controller_Base_Public
{
	/**
	 * The application homepage
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		$this->template = \Theme::instance()->view('templates/subpage');
		$this->template->content = \View::forge('about/index');
	}

}
