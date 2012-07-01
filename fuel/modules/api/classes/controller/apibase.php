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

class Controller_Apibase extends \Controller_Base_Public
{
	/*
	 * setup the documentation details page
	 */
	protected function buildpage()
	{
		// add the docs index page partial to the template
		$partial = \Theme::instance()->set_partial('content', 'api/index');

		// load the defined FuelPHP versions from the database, ordered by version
		$versions = \DB::select()
			->from('versions')
			->order_by('major', 'ASC')
			->order_by('minor', 'ASC')
			->order_by('created_at', 'ASC')
			->execute();

		// create the dropdown array
		$dropdown = array();
		foreach ($versions as $record)
		{
			$dropdown[$record['id']] = $record['major'].'.'.$record['minor'].'/'.$record['branch'];
		}

		// add the version dropdown and the selected version and page to the template partial
		$partial->set(array('versions' => $dropdown, 'version' => \Session::get('version')));

		return $partial;
	}

	/*
	 * render the API docs for this file
	 */
	protected function renderpage($file = null, $view = 'file')
	{
		// if no file hash passed to render, return the intro partial
		if ( ! $file)
		{
			return \Theme::instance()->view('api/intro');
		}

		// fetch the file by hash. note: this is not unique, but an
		// identical hash means an indentical file, so we don't care
		if ( ! $model = Model_Docblox::find()->where('hash', '=', $file)->related('constant')->related('function')->related('class')->related('class.methods')->get_one())
		{
			// bail out to the main page if we didn't find it
			\Messages::redirect('api/packages');
		}

		return \Theme::instance()->view('api/'.$view)->set('model', $model);
	}

	/*
	 * check if the current user has access to the requested action
	 *
	 * @return bool
	 */
	protected function checkaccess()
	{
		if ( ! \Auth::has_access('access.staff') and ! \Session::get('ninjauth.authentication.provider', false) == 'github')
		{
			// nope, inform the user and don't do anything
			\Messages::error('You don\'t have the requested rights to this page!');

			return false;
		}

		return true;
	}
}
