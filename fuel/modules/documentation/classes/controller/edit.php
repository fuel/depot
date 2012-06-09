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

class Controller_Edit extends Controller_Pagebase
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

		// load the latest version of the docs for this page
		$doc = Model_Doc::find()->where('page_id', '=', $page->id)->order_by('created_at', 'DESC')->get_one();

		// create the page partial
		$partial = $this->buildpage($page);

		// add the edit page partial
		$details = \Theme::instance()->view('documentation/editpage');

		// do we have something posted?
		if (\Input::post('page', false) !== false)
		{
			if (\Input::post('cancel'))
			{
				// cancel button used
				\Response::redirect('documentation/page/'.$page->id);
			}

			elseif (\Input::post('submit'))
			{
				// create the validation object
				$val = \Validation::forge('editpage');

				// set the validation rules
				$val->add('title', 'Title')->add_rule('required');
				$val->add('slug', 'Slug')->add_rule('required');
				$val->add('page', 'Page')->add_rule('required');

				if ($val->run())
				{
					// save the changes to the page
					$page->title = $val->validated('title');
					$page->slug = $val->validated('slug');

					// anything changed
					if ($page->is_changed())
					{
						\Cache::delete('documentation.version_'.$page->version_id.'.menu');
					}

					// and save it
					$page->save();

					// any changes to the page
					if ($doc->content == $val->validated('page'))
					{
						// nope, inform the user and don't do anything
						\Messages::warning('No changes were made to the page');
					}
					else
					{
						// get the user id
						$user = \Auth::get_user_id();

						// create a new docs page
						$doc = Model_Doc::forge(array(
							'page_id' => $page->id,
							'user_id' => $user[1],
							'content' => $val->validated('page'),
						));
						$doc->save();

						// delete the page cache
						\Cache::delete('documentation.version_'.$page->version_id.'.page_'.$page->id);

						// nope, inform the user and don't do anything
						\Messages::success('Page successfully saved!');
					}

					// and return to the page
					\Response::redirect('documentation/page/'.$page->id);
				}

				// set any error messages we need to display
				\Messages::error($val->error());

				// set the page variables on the view
				$details
					->set('title', \Input::post('title'))
					->set('slug', \Input::post('slug'))
					->set('page', \Input::post('page'));
			}

			elseif (\Input::post('preview'))
			{
				// set the page variables on the view
				$details
					->set('title', \Input::post('title'))
					->set('slug', \Input::post('slug'))
					->set('page', \Input::post('page'))
					->set('preview', $this->renderpage(htmlentities(\Input::post('page'), ENT_NOQUOTES)), false);
			}
		}
		else
		{
			// set the page variables on the view
			$details
				->set('title', $page->title)
				->set('slug', $page->slug)
				->set('page', $doc ? $doc->content : '')
				->set('preview', false);
		}

		// and set the partial
		$partial->set('details', $details);
	}

}
