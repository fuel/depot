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

namespace Admin;

class Controller_Admin_Branches extends Controller_Base
{
	/**
	 * @var	array	data to send to the view
	 */
	protected $data = array();

	/**
	 * action preparation method
	 */
	public function before()
	{
		// call the base controllers before method
		parent::before();
	}

	/**
	 * version overview page
	 */
	public function action_index($page = 1)
	{
		// make sure $page contains something usefull
		if ( ! is_numeric($page) or $page < 1)
		{
			$page = 1;
		}

		// pages internally are zero offset
		$page--;

		// set pagination information
		$this->pagination['pagination_url'] = \Uri::create('admin/admin/branches/');
		$this->pagination['total_items'] = Model_Version::count();

		\Pagination::set_config($this->pagination);

		// get the records for the current page
		$this->data['versions'] = Model_Version::find()->offset(\Pagination::$offset)->limit(\Pagination::$per_page)->get();

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Source branches');

		// and define the content body
		\Theme::instance()->set_partial('content', 'admin/branches/index')->set($this->data);
	}

	/**
	 * View a source version
	 */
	public function action_view($id = null)
	{
		// get the version record we want to view
		if ( ! $this->data['version'] = Model_Version::find($id))
		{
			// bail out with an error if not found
			\Session::set_flash('error', 'Source branch #'.$id.' does not exist.');
			\Response::redirect('admin/admin/branches');
		}

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Source branches');

		// and define the content body
		\Theme::instance()->set_partial('content', 'admin/branches/view')->set($this->data);
	}

	/**
	 * Create a new version
	 */
	public function action_create($id = null)
	{
		// any data posted?
		if (\Input::method() == 'POST')
		{
			// run the validation rules on the input
			$val = Model_Version::validate('create');
			if ($val->run())
			{
				// create the version object from posted data
				$this->data['version'] = Model_Version::forge(array(
					'major' => \Input::post('major'),
					'minor' => \Input::post('major'),
					'branch' => \Input::post('branch'),
					'default' => \Input::post('default'),
					'codepath' => \Input::post('codepath'),
					'docspath' => \Input::post('docspath'),
					'docbloxpath' => \Input::post('docbloxpath'),
				));

				if ($this->data['version'] and $this->data['version']->save())
				{
					// create a copy of the documentation for this version
					if (\Input::post('docsversion', 0))
					{
						// get all pages
						$pages = Model_Page::find()->where('version_id', '=', \Input::post('docsversion', 0))->get();
						foreach ($pages as $id => $page)
						{
							// get the latest docs for this page
							$doc = Model_Doc::find()->where('page_id', '=', $page->id)->order_by('created_at', 'DESC')->get_one();

							// convert the page to an array and make it new data
							$page = $page->to_array();
							unset($page['id']);
							$page['version_id'] = $this->data['version']->id;

							// insert the new page
							$newpage = Model_Page::forge($page);
							$newpage->save();

							// copy the page doc too if it was present
							if ($doc)
							{
								$doc = $doc->to_array();
								unset($doc['id']);
								$doc['page_id'] = $newpage->id;
								Model_Doc::forge($doc)->save();
							}
						}
					}

					\Session::set_flash('success', 'Added source branch #'.$this->data['version']->id.'.');
					\Response::redirect('admin/admin/branches');
				}
				else
				{
					\Session::set_flash('error', 'Could not save Source Branch.');
				}
			}
			else
			{
				// validation errors, show them
				\Session::set_flash('error', $val->show_errors());
			}
		}

		// determine the current versions
		$this->data['versions'] = array(0 => '&nbsp;');
		foreach (Model_Version::find('all') as $version)
		{
			$this->data['versions'][$version->id] = $version->major.'.'.$version->minor.'/'.$version->branch;
		}

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Source branches');

		// and define the content body
		\Theme::instance()->set_partial('content', 'admin/branches/create')->set($this->data);
	}

	/**
	 * Edit a user
	 */
	public function action_edit($id = null)
	{
		// get the version record we want to edit
		if ( ! $version = Model_Version::find($id))
		{
			// bail out with an error if not found
			\Session::set_flash('error', 'Source branch #'.$id.' does not exist.');
			\Response::redirect('admin/admin/branches');
		}

		// run the validation rules on the input
		$val = Model_Version::validate('edit');
		if ($val->run())
		{
			// populate the object from the input
			$version->major = \Input::post('major');
			$version->minor = \Input::post('minor');
			$version->branch = \Input::post('branch');
			$version->default = \Input::post('default');
			$version->codepath = \Input::post('codepath');
			$version->docspath = \Input::post('docspath');
			$version->docbloxpath = \Input::post('docbloxpath');

			// and save it
			if ($version->save())
			{
				// if this one is default, reset any others
				$version->default and $query = Model_Version::query()->set('default', 0)->where('id', '!=', $id)->update();

				\Session::set_flash('success', 'Updated source branch #' . $id);
				\Response::redirect('admin/admin/branches');
			}

			else
			{
				\Session::set_flash('error', 'Could not update source branch #' . $id);
			}
		}

		else
		{
			// validation failed, was there input?
			if (\Input::method() == 'POST')
			{
				// populate the object from the validation data
				$version->major = $val->validated('major');
				$version->minor = $val->validated('minor');
				$version->branch = $val->validated('branch');
				$version->default = $val->validated('default');
				$version->codepath = $val->validated('codepath');
				$version->docspath = $val->validated('docspath');
				$version->docbloxpath = $val->validated('docbloxpath');

				// and display the validation errors
				\Session::set_flash('error', $val->show_errors());
			}
		}

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Source branches');

		// and define the content body
		\Theme::instance()->set_partial('content', 'admin/branches/edit')->set($this->data)->set('version', $version, false);
	}

	/**
	 * Delete a user
	 */
	public function action_delete($id = null)
	{
		if ($version = Model_Version::find($id))
		{
			$version->delete();

			\Session::set_flash('success', 'Deleted source branch #'.$id);
		}

		else
		{
			\Session::set_flash('error', 'Could not delete source branch #'.$id);
		}

		\Response::redirect('admin/admin/branches');

	}

}
