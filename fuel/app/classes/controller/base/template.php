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

class Controller_Base_Template extends Controller_Template
{
	/**
	* @var string page template
	*/
	public $template = null;

	/**
	* @var boolean auto render template
	**/
	public $auto_render = true;

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
		// do we have a template view loaded?
		if ( ! $this->template instanceOf View)
		{
			// no, so do that now
			if ( is_string($this->template))
			{
				$this->template = \Theme::instance()->view($this->template);
			}
			else
			{
				$this->template = \Theme::instance()->view('templates/layout');
			}
		}

		// create an asset instance for our theme
		\Theme::instance()->asset = \Asset::forge('theme', array('paths' => array(\Config::get('theme.paths.0').DS.\Config::get('theme.active').DS.'assets'.DS)));

		// define the navbar
		$navbar = \Theme::instance()->view('partials/page/navbar');

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
		}
		else
		{
			$navitems[] = array('name' => 'Login', 'link' => '/users/login', 'class' => '');
		}
		// see if we need to highlight one
		$uri = '/'.\Request::active()->uri->uri;
		foreach ($navitems as $navitem)
		{
			if (strpos($uri, $navitem['link']) === 0)
			{
				$navitem['class'] .= ' current';
			}
			isset($this->navbar[$navitem['name']]) or $this->navbar[$navitem['name']] = $navitem;
		}
		$navbar->set('navitems', $this->navbar);
		$this->template->set('navbar', $navbar);

		// call the parent controller
		parent::before();
	}
}
