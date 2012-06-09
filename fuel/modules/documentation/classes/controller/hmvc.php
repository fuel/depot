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

namespace Documentation;

class Controller_Hmvc extends \Controller
{
	/**
	 * before action processing.
	 */
	public function before()
	{
		// this controller only serves HMVC calls
		if ( ! \Request::is_hmvc())
		{
			throw new \HttpNotFoundException();
		}
	}

}
