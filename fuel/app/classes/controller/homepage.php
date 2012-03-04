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
		// if this is a request for the index action, switch to the homepage layout
		\Request::active()->action == 'index' and $this->template = 'templates/homepage';

		// call the parent to setup the page template
		parent::before();
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
		$this->template->content = \View::forge('about');
	}

	/**
	 * The application 404 page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		$this->template->content = \Theme::instance()->view('partials/page/404');
	}

}
