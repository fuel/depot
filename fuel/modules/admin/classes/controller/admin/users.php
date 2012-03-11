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

class Controller_Admin_Users extends Controller_Base
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

		// get the groupnames
		$this->data['groupnames'] = \Config::get('simpleauth.groups');

		// get the groups for the dropdown
		$this->data['groups'] = array();
		foreach ($this->data['groupnames'] as $value => $group)
		{
			$value == 0 or $this->data['groups'][$value] = $group['name'];
		}
	}

	/**
	 * User overview page
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
		$this->pagination['pagination_url'] = \Uri::create('admin/users/');
		$this->pagination['total_items'] = Model_User::count();

		\Pagination::set_config($this->pagination);

		// get the records for the current page
		$this->data['users'] = Model_User::find()->offset(\Pagination::$offset)->limit(\Pagination::$per_page)->get();

		// load the template partial
		$this->template->title = "Users";
		$this->template->content = \View::forge('users/index', $this->data);

	}

	/**
	 * View a user
	 */
	public function action_view($id = null)
	{
		// get the user record we want to view
		if ( ! $this->data['user'] = Model_User::find($id))
		{
			// bail out with an error if not found
			\Session::set_flash('error', 'User #'.$id.' does not exist.');
			\Response::redirect('admin/users');
		}

		// load the template partial
		$this->template->title = "User";
		$this->template->content = \View::forge('users/view', $this->data);
	}

	/**
	 * Create a new user
	 */
	public function action_create($id = null)
	{
		// any data posted?
		if (\Input::method() == 'POST')
		{
			// run the validation rules on the input
			$val = Model_User::validate('create');
			if ($val->run())
			{
				// create the user object from posted data
				try
				{
					$profile = array('full_name' => \Input::post('full_name'));
					$id = \Auth::create_user(\Input::post('username'), \Input::post('password'), \Input::post('email'), \Input::post('group'), $profile);

					// if a user object is created, save it
					\Session::set_flash('success', 'Added user #'.$id.'.');
					\Response::redirect('admin/users');
				}
				catch (\Exception $e)
				{
					// else display an error
					\Session::set_flash('error', $e->getMessage());
				}
			}
			else
			{
				// validation errors, show them
				\Session::set_flash('error', $val->show_errors());
			}
		}

		// load the template partial
		$this->template->title = "Users";
		$this->template->content = \View::forge('users/create', $this->data);

	}

	/**
	 * Edit a user
	 */
	public function action_edit($id = null)
	{
		// get the user record we want to edit
		if ( ! $user = Model_User::find($id))
		{
			// bail out with an error if not found
			\Session::set_flash('error', 'User #'.$id.' does not exist.');
			\Response::redirect('admin/users');
		}


		// run the validation rules on the input
		$val = Model_User::validate('edit');
		if ($val->run())
		{
			// populate the object from the input
			$user->username = \Input::post('username');
			$user->profile_fields['full_name'] = \Input::post('full_name');
			$user->email = \Input::post('email');
			$user->group = \Input::post('group');
			if (\Input::post('password'))
			{
				$user->password = \Auth::hash_password(\Input::post('password'));
			}

			// and save it
			if ($user->save())
			{
				\Session::set_flash('success', 'Updated user #' . $id);
				\Response::redirect('admin/users');
			}

			else
			{
				\Session::set_flash('error', 'Could not update user #' . $id);
			}
		}

		else
		{
			// validation failed, was there input?
			if (\Input::method() == 'POST')
			{
				// populate the object from the validation data
				$user->username = $val->validated('username');

				// and display the validation errors
				\Session::set_flash('error', $val->show_errors());
			}
		}

		$this->template->title = "Users";
		$this->template->content = \View::forge('users/edit', $this->data)->set('user', $user, false);

	}

	/**
	 * Delete a user
	 */
	public function action_delete($id = null)
	{
		if ($user = Model_User::find($id))
		{
			$user->delete();

			\Session::set_flash('success', 'Deleted user #'.$id);
		}

		else
		{
			\Session::set_flash('error', 'Could not delete user #'.$id);
		}

		\Response::redirect('admin/users');

	}

}
