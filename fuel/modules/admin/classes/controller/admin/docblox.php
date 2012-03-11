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

class Controller_Admin_Docblox extends Controller_Base
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
		$this->pagination['pagination_url'] = \Uri::create('admin/docblox/');
		$this->pagination['total_items'] = Model_Version::count();

		\Pagination::set_config($this->pagination);

		// get the records for the current page
		$this->data['versions'] = Model_Version::find()->offset(\Pagination::$offset)->limit(\Pagination::$per_page)->get();

		// load the template partial
		$this->template->title = "Source branches";
		$this->template->content = \View::forge('docblox/index', $this->data);

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
			\Response::redirect('admin/docblox');
		}

		// load the template partial
		$this->template->title = "Source branch";
		$this->template->content = \View::forge('docblox/view', $this->data);
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
				));

				if ($this->data['version'] and $this->data['version']->save())
				{
					\Session::set_flash('success', 'Added source branch #'.$this->data['version']->id.'.');
					\Response::redirect('admin/docblox');
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

		// load the template partial
		$this->template->title = "Source Branch";
		$this->template->content = \View::forge('docblox/create', $this->data);
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
			\Response::redirect('admin/docblox');
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
				\Response::redirect('admin/docblox');
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

		$this->template->title = "Source Branch";
		$this->template->content = \View::forge('docblox/edit', $this->data)->set('version', $version, false);
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

		\Response::redirect('admin/docblox');

	}

}
