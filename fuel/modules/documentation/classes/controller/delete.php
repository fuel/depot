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
		$page or \Response::redirect('documentation');

		// validate the access
		if ( ! $this->checkaccess())
		{
			\Response::redirect('documentation/page/'.$page->id);
		}

		if (\Input::post('cancel'))
		{
			// cancel button used
			\Response::redirect('documentation/page/'.$page->id);
		}

		elseif (\Input::post('confirm'))
		{
			$page->tree_delete();

			// delete the menu cache so it will be refreshed
			\Cache::delete('documentation.version_'.$page->version_id.'.menu');

			// inform the user the page is a goner
			\Messages::success('The page is succesfully deleted!');

			\Response::redirect('documentation');
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
