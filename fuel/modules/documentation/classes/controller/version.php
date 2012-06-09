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

class Controller_Version extends \Controller_Base_Public
{
	/**
	 * Documentation version index page, determine which default page to load
	 */
	public function action_index($version = null)
	{
		// if no version is given, go and determine it
		$version === null and \Response::redirect('documentation');

		// delete any stored version in the session
		\Session::delete('version');

		// check if requested version exists
		$version = Model_Version::find($version);

		// if no version is found, go and determine it
		$version === null and \Response::redirect('documentation');

		// store the version id in the session
		\Session::set('version', $version->id);

		// see if we can find a default page for the requested version
		if ( ! $page = Model_Page::find()->where('version_id', '=', $version->id)->where('default', '=', 1)->get_one())
		{
			// no default page exists for this version. Find the first page node
			if ( ! $page = Model_Page::find()->where('version_id', '=', $version->id)->where('left_id', '>', 2)->where('right_id', '=', \DB::expr('left_id + 1'))->get_one())
			{
				// no page found, have the page controller deal with it
				$page = (object) array('id' => '');
			}
		}

		// have the page controller deal with the pages
		\Response::redirect('documentation/page/'.$page->id);
	}

}
