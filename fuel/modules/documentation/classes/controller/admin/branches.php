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

class Controller_Admin_Branches extends \Admin\Controller_Base
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
		$this->pagination['pagination_url'] = \Uri::create('documentation/admin/branches/');
		$this->pagination['total_items'] = Model_Version::count();

		\Pagination::set_config($this->pagination);

		// get the records for the current page
		$this->data['versions'] = Model_Version::find()->offset(\Pagination::$offset)->limit(\Pagination::$per_page)->order_by('major', 'ASC')->order_by('minor', 'ASC')->order_by('branch', 'ASC')->get();

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Source branches');

		// and define the content body
		\Theme::instance()->set_partial('content', 'documentation/admin/branches/index')->set($this->data);
	}

	/**
	 * View a source version
	 */
	public function action_view($id = null)
	{
		// get the version record we want to view
		if ( ! $this->data['version'] = \Documentation\Model_Version::find($id))
		{
			// bail out with an error if not found
			\Messages::error('Source branch #'.$id.' does not exist.');
			\Messages::redirect('documentation/admin/branches');
		}

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Source branches');

		// and define the content body
		\Theme::instance()->set_partial('content', 'documentation/admin/branches/view')->set($this->data);
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
			$val = \Documentation\Model_Version::validate('create');
			if ($val->run())
			{
				// create the version object from posted data
				$this->data['version'] = Model_Version::forge(array(
					'major' => \Input::post('major'),
					'minor' => \Input::post('major'),
					'branch' => \Input::post('branch'),
					'default' => \Input::post('default'),
					'editable' => \Input::post('editable'),
					'codepath' => \Input::post('codepath'),
					'docspath' => \Input::post('docspath'),
					'docbloxpath' => \Input::post('docbloxpath'),
				));

				if ($this->data['version'] and $this->data['version']->save())
				{
					// create a copy of the documentation for this version
					if (\Input::post('docsversion', 0))
					{
						$this->copy_docs(\Input::post('docsversion', 0), $this->data['version']->id);
					}
					else
					{
						// create a new page tree root for this branch
						$page = \Documentation\Model_Page::forge(array(
							'title' => '---',
							'slug' => '',
							'user_id' => 1,
							'default' => 0,
							'editable' => 0,
							'updated_at' => 0)
						)->tree_new_root();

						// update the version to match the branch version id
						$page->version = $this->data['version'];
						$page->save();
					}

					\Messages::success('Added source branch #'.$this->data['version']->id.'.');
					\Messages::redirect('documentation/admin/branches');
				}
				else
				{
					\Messages::error('Could not save Source Branch.');
				}
			}
			else
			{
				// and display the validation errors
				foreach($val->error() as $e)
				{
					\Messages::error($e->get_message());
				}
			}
		}

		// determine the current versions
		$this->data['versions'] = $this->get_versions();

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Source branches');

		// and define the content body
		\Theme::instance()->set_partial('content', 'documentation/admin/branches/create')->set($this->data);
	}

	/**
	 * Edit a user
	 */
	public function action_edit($id = null)
	{
		// get the version record we want to edit
		if ( ! $version = \Documentation\Model_Version::find($id))
		{
			// bail out with an error if not found
			\Messages::error('Source branch #'.$id.' does not exist.');
			\Messages::redirect('documentation/admin/branches');
		}

		// run the validation rules on the input
		$val = \Documentation\Model_Version::validate('edit');
		if ($val->run())
		{
			// populate the object from the input
			$version->major = \Input::post('major');
			$version->minor = \Input::post('minor');
			$version->branch = \Input::post('branch');
			$version->default = \Input::post('default');
			$version->editable = \Input::post('editable');
			$version->codepath = \Input::post('codepath');
			$version->docspath = \Input::post('docspath');
			$version->docbloxpath = \Input::post('docbloxpath');

			// and save it
			if ($version->save())
			{
				// if this one is default, reset any others
				$version->default and $query = \Documentation\Model_Version::query()->set('default', 0)->where('id', '!=', $id)->update();

				// create a copy of the documentation if needed
				if (\Input::post('docsversion', 0))
				{
					$this->copy_docs(\Input::post('docsversion', 0), $version->id);
				}

				\Messages::success('Updated source branch #' . $id);
				\Messages::redirect('documentation/admin/branches');
			}

			else
			{
				\Messages::error('Could not update source branch #' . $id);
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
				$version->editable = \Input::post('editable');
				$version->codepath = $val->validated('codepath');
				$version->docspath = $val->validated('docspath');
				$version->docbloxpath = $val->validated('docbloxpath');

				// and display the validation errors
				foreach($val->error() as $e)
				{
					\Messages::error($e->get_message());
				}
			}
		}

		// determine the current versions
		$this->data['versions'] = $this->get_versions();

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Source branches');

		// and define the content body
		\Theme::instance()->set_partial('content', 'documentation/admin/branches/edit')->set($this->data)->set('version', $version, false);
	}

	/**
	 * Delete a user
	 */
	public function action_delete($id = null)
	{
		if ($version = \Documentation\Model_Version::find($id))
		{
			$version->delete();

			\Messages::success('Deleted source branch #'.$id);
		}

		else
		{
			\Messages::error('Could not delete source branch #'.$id);
		}

		\Messages::redirect('documentation/admin/branches');

	}

	/**
	 * load the available documenation versions
	 */
	protected function get_versions()
	{
		// determine the current versions
		$data = array(0 => '&nbsp;');

		foreach (\Documentation\Model_Version::find()->order_by('major', 'ASC')->order_by('minor', 'ASC')->order_by('branch', 'ASC')->get() as $version)
		{
			$data[$version->id] = $version->major.'.'.$version->minor.'/'.$version->branch;
		}

		return $data;
	}

	protected function copy_docs($from_version, $to_version)
	{
		// delete any docs pages present for the to_version
		$pages = \Documentation\Model_Page::find()->where('version_id', '=', $to_version)->get();
		$pages and $pages->delete();

		// get all pages of the from_version
		$pages = \Documentation\Model_Page::find()->where('version_id', '=', $from_version)->get();
		foreach ($pages as $id => $page)
		{
			// get the latest docs for this page
			$doc = \Documentation\Model_Doc::find()->where('page_id', '=', $page->id)->order_by('created_at', 'DESC')->get_one();

			// convert the page to an array and make it new data
			$page = $page->to_array();
			unset($page['id']);
			$page['version_id'] = $to_version;

			// insert the new page
			$newpage = \Documentation\Model_Page::forge($page);
			$newpage->save();

			// copy the page doc too if it was present
			if ($doc)
			{
				$doc = $doc->to_array();
				unset($doc['id']);
				$doc['page_id'] = $newpage->id;
				\Documentation\Model_Doc::forge($doc)->save();
			}
		}

		\Messages::success('Branch documenation copied from #'.$from_version.' to  #'.$to_version);

	}

}
