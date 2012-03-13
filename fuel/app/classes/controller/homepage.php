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
	 * The application homepage
	 *
	 * @access  public
	 * @return  Response
	 */
	public function before()
	{
		// call the parent to run the setup
		parent::before();

		// if this is a request for the index action, switch to the homepage layout template
		\Request::active()->action == 'index' and \Theme::instance()->set_template('templates/homepage');

	}

	/**
	 * The application homepage
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// no code, the homepage is static atm
	}

	/**
	 * The about page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_about()
	{
		\Theme::instance()->set_partial('content', 'about');
	}

	/**
	 * The application 404 page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		\Theme::instance()->set_partial('content', 'global/404');
	}

}
