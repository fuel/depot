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

class Controller_Base_Template extends Controller_Hybrid
{
	/**
	* @var string page template
	*/
	public $template = 'templates/layout';

	/**
	* @var string global date format to use
	*/
	public $date_format = 'eu';

	/**
	* @var array navigation bar entries
	*/
	public $navbar = array();

	/**
	 * @param   none
	 * @throws  none
	 * @returns	void
	 */
	public function before()
	{
		if (\Input::is_ajax())
		{
			return parent::before();
		}

		// define the theme template to use for this page, set a default if needed
		\Theme::instance()->set_template($this->template);

		// define the navbar
		$navitems = array(
			array('name' => 'About', 'link' => '/about', 'class' => ''),
			array('name' => 'Documentation', 'link' => '/documentation', 'class' => ''),
			array('name' => 'Class API', 'link' => '/api', 'class' => ''),
			array('name' => 'Tutorials', 'link' => '/tutorials', 'class' => ''),
			array('name' => 'Screencasts', 'link' => '/screencasts', 'class' => ''),
			array('name' => 'Snippets', 'link' => '/snippets', 'class' => ''),
			array('name' => 'Cells', 'link' => '/cells', 'class' => ''),
			array('name' => 'Forums', 'link' => 'http://fuelphp.com/forums', 'class' => ''),
		);
		if (\Auth::check())
		{
			$navitems[] = array('name' => 'Profile', 'link' => '/users/profile', 'class' => '');
			$navitems[] = array('name' => 'Logout', 'link' => '/users/logout', 'class' => '');

			// set the correct timezone for logged in users
			$profile = \Auth::get_profile_fields();

			isset($profile['timezone']) and \Date::display_timezone($profile['timezone']);
			isset($profile['dateformat']) and $this->date_format = $profile['dateformat'];
		}
		else
		{
			$navitems[] = array('name' => 'Login', 'link' => '/users/login', 'class' => '');
		}
		// see if we need to highlight one
		$uri = '/'.\Request::active()->uri->uri;
		foreach ($navitems as $navitem)
		{
			// highlight the current navigation item
			strpos($uri, $navitem['link']) === 0 and $navitem['class'] .= ' current';

			// and set the navbar item if not already set
			empty($this->navbar[$navitem['name']]) and $this->navbar[$navitem['name']] = $navitem;
		}

		// define the navbar partial and add the navbar data
		\Theme::instance()->set_partial('navbar', 'templates/navbar')->set('navitems', $this->navbar);
	}

	/**
	 * After controller method has run, render the theme template
	 *
	 * @param  Response  $response
	 */
	public function after($response)
	{
		if ( ! \Input::is_ajax())
		{
			// If nothing was returned set the theme instance as the response
			if (empty($response))
			{
				$response = \Response::forge(\Theme::instance());
			}

			// If the response isn't a Response object, embed in the available one for BC
			// @deprecated  can be removed when $this->response is removed
			if ( ! $response instanceof Response)
			{
				$this->response->body = $response;
				$response = $this->response;
			}

			return $response;
		}

		return parent::after($response);
	}
}
