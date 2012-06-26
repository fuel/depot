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

namespace Api;

class Controller_Version extends \Controller_Base_Public
{
	/**
	 * Documentation version index page, determine which default page to load
	 */
	public function action_index($version = null)
	{
		// if no version is given, go and determine it
		$version === null and \Response::redirect('api');

		// delete any stored version in the session
		\Session::delete('version');

		// check if requested version exists
		\Module::load('documentation');
		$version = \Documentation\Model_Version::find($version);

		// if no version is found, go and determine it
		$version === null and \Response::redirect('api');

		// store the version id in the session
		\Session::set('version', $version->id);

		// fetch the type of API docs we need to display
		if ($type = \Input::post('apitype', false))
		{
			\Session::set('apitype', $type);
		}
		else
		{
			$type = \Session::get('apitype', 'packages');
		}

		// and have the type controller deal with the pages
		\Response::redirect('api/'.$type);
	}

}
