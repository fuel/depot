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

class Controller_Api extends \Controller_Base_Public
{
	/**
	 * Api index page, determine which api version to load
	 */
	public function action_index()
	{
		// do we have a version stored in the session?
		if ($version = \Session::get('version', false))
		{
			\Messages::redirect('api/version/'.$version);
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
				\Messages::redirect('api/version/'.$record['id']);
			}
		}

		// get the latest if no default is defined
		if ($versions->count())
		{
			\Messages::redirect('api/version/'.$record['id']);
		}

		// giving up, no versions found, show an error message
		\Theme::instance()->set_partial('content', 'api/error', true);
	}

}
