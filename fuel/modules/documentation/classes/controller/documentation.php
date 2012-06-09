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

class Controller_Documentation extends \Controller_Base_Public
{
	/**
	 * Documentation index page, determine which docs version to load
	 */
	public function action_index()
	{
		// do we have a version stored in the session?
		if ($version = \Session::get('version', false))
		{
			\Response::redirect('documentation/version/'.$version);
		}

		// load the defined FuelPHP versions from the database, ordered by version
		$versions = \DB::select()
			->from('versions')
			->order_by('major', 'ASC')
			->order_by('minor', 'ASC')
			->order_by('created_at', 'ASC')
			->execute();

		// find the default version
		foreach ($versions as $record)
		{
			if ($record['default'])
			{
				\Response::redirect('documentation/version/'.$record['id']);
			}
		}

		// get the latest if no default is defined
		if ($versions->count())
		{
			\Response::redirect('documentation/version/'.$record['id']);
		}

		// giving up, no versions found, show an error message
		\Theme::instance()->set_partial('content', 'documentation/error', true);
	}

}
