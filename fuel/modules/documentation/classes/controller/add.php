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

class Controller_Add extends Controller_Pagebase
{
	/**
	 * Documentation add a new page
	 */
	public function action_index($version = null)
	{
		// validate the access
		if ( ! $this->checkaccess())
		{
			\Response::redirect('documentation');
		}

		// create a dummy page object
		$page = (object) array('id' => 0, 'title' => '', 'slug' => '', 'node' => 0);

		// build the page layout partial
		$partial = $this->buildpage($page);

		// add the edit page partial
		$details = \Theme::instance()->view('documentation/addpage');

		// do we have something posted?
		if (\Input::post('page', false) !== false)
		{
			if (\Input::post('cancel'))
			{
				// cancel button used
				\Response::redirect('documentation/version/'.$version);
			}
			elseif (\Input::post('submit'))
			{
				// create the validation object
				$val = \Validation::forge('editpage');

				// set the validation rules
				$val->add('title', 'Title')->add_rule('required');
				$val->add('slug', 'Slug')->add_rule('required');

				if ($val->run())
				{
					// fetch the previous sibling node
					if ($model = Model_Page::find(\Input::post('node')))
					{
						// get the user id
						$user = \Auth::get_user_id();

						// create the new page
						$page = Model_Page::forge(array(
							'user_id' => $user[1],
							'default' => 0,
							'editable' => 1,
							'title' => $val->validated('title'),
							'slug' => $val->validated('slug'),
							'updated_at' => 0,
						));

						// and create the new page entry
						if ($page->tree_is_root())
						{
							// the root has no siblings
							$page->tree_new_first_child_of($model);
						}
						else
						{
							// as next silbling of the selected node
							$page->tree_new_next_sibling_of($model);
						}

						// delete the menu cache so it will be refreshed
						\Cache::delete('documentation.version_'.$page->version_id.'.menu');

						// create a new docs page
						if ($doc = \Input::post('page', false))
						{
							$doc = Model_Doc::forge(array(
								'page_id' => $page->id,
								'user_id' => $user[1],
								'content' => $doc,
							));
							$doc->save();
						}

						// inform the user the page has been created
						\Messages::success('Page successfully created!');

						// return to the created page
						\Response::redirect('documentation/page/'.$page->id);
					}
					else
					{
						// inform the user the parent node can not be found
						\Messages::info('Selected previous page no longer exists');
					}
				}

				// set any error messages we need to display
				\Messages::error($val->error());

				// set the page variables on the view
				$details
					->set('title', \Input::post('title'))
					->set('slug', \Input::post('slug'))
					->set('node', \Input::post('node'))
					->set('page', \Input::post('page'));
			}
			elseif (\Input::post('preview'))
			{
				// set the page variables on the view
				$details
					->set('title', \Input::post('title'))
					->set('slug', \Input::post('slug'))
					->set('page', \Input::post('page'))
					->set('node', \Input::post('node'))
					->set('preview', $this->renderpage(htmlentities(\Input::post('page'), ENT_NOQUOTES)), false);
			}
		}
		else
		{
			// set the page variables on the view
			$details
				->set('title', $page->title)
				->set('slug', $page->slug)
				->set('page', '')
				->set('node', 0)
				->set('preview', false);
		}

		// load the tree for this version
		if ($model = Model_Page::forge()->tree_select(\Session::get('version'))->tree_get_root())
		{
			// fetch the menu tree
			$details->set('pagetree', $model->tree_dump_dropdown('title'));
		}
		else
		{
			// no existing pages for this version
			$details->set('pagetree', array(0 => ''));
		}

		// and set the partial
		$partial->set('details', $details);
	}

}
