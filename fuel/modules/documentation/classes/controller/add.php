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
		$page = (object) array('id' => 0, 'title' => '', 'slug' => '', 'node' => 0, 'editable' => 1);

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
				$val->add('insert', 'Insert type')->add_rule('required')->add_rule('is_numeric');
				$val->add('node', 'Menu node ID')->add_rule('required')->add_rule('is_numeric');

				if ($val->run())
				{
					// get the parent node
					$node = \Input::post('node', false);
					if ($insert = \Input::post('insert', false))
					{
						$parent = Model_Page::find($node);
					}
					else
					{
						$parent = Model_Page::forge()->tree_select(\Session::get('version'))->tree_get_root();
					}

					// do we have a parent node?
					if ($parent)
					{
						// get the current user's id
						list($notused, $userid) = \Auth::get_user_id();

						// create the new page
						$page = Model_Page::forge(array(
							'user_id' => $userid,
							'default' => 0,
							'editable' => 1,
							'title' => $val->validated('title'),
							'slug' => $val->validated('slug'),
							'updated_at' => 0,
						));

						// and create the new page entry
						if ($insert == 0)
						{
							// create the new page as a child of the parent
							$page->tree_new_last_child_of($parent);
						}
						elseif ($insert == 1)
						{
							// create the new page as a sibling of the parent
							$page->tree_new_next_sibling_of($parent);
						}
						elseif ($insert == 2)
						{
							// create the new page as a child of the parent
							$page->tree_new_first_child_of($parent);
						}
						else
						{
							// inform the user there was an error
							\Messages::error('Invalid insert method. Page could not be inserted!');

							// return to the created page
							\Response::redirect('documentation/page/'.$page->id);
						}

						// delete the menu cache so it will be refreshed
						\Cache::delete('documentation.version_'.$page->version_id.'.menu');

						// create a new docs page
						if ($doc = \Input::post('page', false))
						{
							$doc = Model_Doc::forge(array(
								'page_id' => $page->id,
								'user_id' => $userid,
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
						\Messages::info('Selected page node no longer exists. Page could not be inserted.');
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
		if ($model = Model_Page::forge()->tree_select(\Session::get('version'))->tree_get_root() and $model->tree_has_children())
		{
			// fetch the menu tree
			$details->set('pagetree', $this->menu_chapters_tree($model ,'title'));
		}
		else
		{
			// no existing pages for this version
			$details->set('pagetree', array(0 => '---'));
		}

		// and set the partial
		$partial->set('details', $details);
	}

	/**
	 * custom tree dropdown, find all nodes that can be parent page nodes
	 */
	protected function menu_chapters_tree(\Nestedsets\Model $model, $field = null, $skip_root = false)
	{
		// set the name field
		empty($field) and $field = $model->tree_get_property('title_field');

		// we need a name field to generate the tree
		if ( ! is_null($field))
		{
			// fetch the tree into an array
			$result = $model->tree_dump_as_array(array('id', $field), $skip_root);

			// storage for the dropdown tree
			$tree = array();

			if ($result)
			{
				// loop trough the tree, fetch all nodes with children or without any docs pages
				foreach ($result as $key => $value)
				{
					if ($value[$model->tree_get_property('right_field')] - $value[$model->tree_get_property('left_field')] > 1 or Model_Doc::find()->where('page_id', $value['id'])->count() == 0)
					{
						$tree[$value['_key_']] = str_repeat('&nbsp;', ($value['_level_']) * 3) . ($value['_level_'] ? '&raquo; ' : '') . $value[$field];
					}
				}
			}

			// return the result
			return $tree;
		}
		else
		{
			return false;
		}
	}
}
