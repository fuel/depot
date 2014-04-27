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

class Controller_Page extends Controller_Pagebase
{
	/**
	 * Documentation page index page
	 */
	public function action_index($page = null)
	{
		// fetch the requested page
		$page === null or $page = Model_Page::find($page);

		// create the page partial
		$partial = $this->buildpage($page);

		// do we have a page?
		if ($page)
		{
			// load the latest version of the docs for this page
			$doc = Model_Doc::query()->where('page_id', '=', $page->id)->order_by('created_at', 'DESC')->get_one();

			// set some data about the last editor, and the time of the last edit
			if ($doc)
			{
				$partial->set('pagedata', array('user' => $doc->user->profile_fields['full_name'],'user_id' => $doc->user_id, 'editable' => $page->editable, 'updated' => $doc->created_at, 'format' => $this->date_format));
			}
			else
			{
				$partial->set('pagedata', false);
			}

			try
			{
				// get the rendered page details from cache
				$details = \Cache::get('documentation.version_'.$page->version_id.'.page_'.$page->id);

			}
			catch (\CacheNotFoundException $e)
			{
				// found it?
				if ($doc)
				{
					// render the page
					$details = $this->renderpage(htmlentities($doc->content, ENT_NOQUOTES));

					// cache the rendered result an hour if not in development
					\Cache::set('documentation.version_'.$page->version_id.'.page_'.$page->id, $details, \Fuel::$env == 'development' ? 60 : 3600);
				}
			}
		}

		// load the not found page
		isset($details) or $details = \Theme::instance()->view('documentation/notfound')->set('page', $page);

		// set the page details
		$partial->set('details', $details, false);
	}

}
