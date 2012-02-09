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
	 * @param   none
	 * @throws  none
	 * @returns	void
	 */
	public function before()
	{
		// load the default page template if none is defined
		if ( ! $this->template instanceOf View)
		{
			$this->template = \Theme::instance()->view('templates/subpage');
		}

		// call the parent controller
		parent::before();
	}
}
