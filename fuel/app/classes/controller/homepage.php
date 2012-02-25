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

class Controller_Homepage extends \Controller_Base_Public
{
	/**
	* @var string page template
	*/
	public $template = 'templates/homepage';

	/**
	 * The application homepage
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
	}

	/**
	 * The application 404 page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		$this->template = \Theme::instance()->view('templates/404');
	}

}
