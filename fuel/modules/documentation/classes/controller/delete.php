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

class Controller_Delete extends Controller_Pagebase
{
	/**
	 * Documentation page index page
	 */
	public function action_index($page = null)
	{
		// fetch the requested page
		$page === null or $page = Model_Page::find($page);

		// do we have a page? we should have!
		$page or \Messages::redirect('documentation');

		// validate the access
		if ( ! $this->checkaccess())
		{
			\Messages::redirect('documentation/page/'.$page->id);
		}

		if (\Input::post('cancel'))
		{
			// cancel button used
			\Messages::redirect('documentation/page/'.$page->id);
		}

		elseif (\Input::post('undo'))
		{
			// do we have a latest docs page
			if ($doc = current($page->latest))
			{
				// only the current user can undo the last action
				if ($userinfo = \Auth::get_user_id())
				{
					list($driver, $id) = $userinfo;
					if ($id == $doc->user_id)
					{
						// delete this docs page
						$doc->delete();

						// delete the cached version of this page
						\Cache::delete('documentation.version_'.$page->version_id.'.page_'.$page->id);

						// inform the user this page version is a goner
						\Messages::success('Succesfully reverted to the previous version of this page');
					}
				}
			}

			\Messages::redirect('documentation/page/'.$page->id);
		}

		elseif (\Input::post('lock'))
		{
			$page->editable = 0;
			$page->save();

			// inform the user the page is a goner
			\Messages::success('This page is now marked read-only');

			// cancel button used
			\Messages::redirect('documentation/page/'.$page->id);
		}

		elseif (\Input::post('unlock'))
		{
			$page->editable = 1;
			$page->save();

			// inform the user the page is a goner
			\Messages::success('Write access has been enabled for this page');

			// cancel button used
			\Messages::redirect('documentation/page/'.$page->id);
		}

		elseif (\Input::post('confirm'))
		{
			$page->tree_delete();

			// delete the menu cache so it will be refreshed
			\Cache::delete('documentation.version_'.$page->version_id.'.menu');

			// inform the user the page is a goner
			\Messages::success('The page is succesfully deleted!');

			\Messages::redirect('documentation');
		}

		// build the page layout partial
		$partial = $this->buildpage($page);

		// load the latest version of the docs for this page
		$doc = Model_Doc::find()->where('page_id', '=', $page->id)->order_by('created_at', 'DESC')->get_one();

		// add the edit page partial
		$details = \Theme::instance()->view('documentation/delpage');
		$details->set('page', $page)->set('doc', $doc ? $this->renderpage($doc->content) : '', false);

		// and set the partial
		$partial->set('details', $details);
	}

}
