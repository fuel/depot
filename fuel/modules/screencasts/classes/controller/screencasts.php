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

namespace Screencasts;

class Controller_Screencasts extends \Controller_Base_Public
{
	/**
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		\Theme::instance()->set_partial('content', \View::forge('screencasts/index'));
	}

}
