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

namespace Users;

class Controller_Admin_Users extends \Admin\Controller_Base
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
		$this->pagination['pagination_url'] = \Uri::create('admin/users/users');
		$this->pagination['total_items'] = Model_User::count();

		\Pagination::set_config($this->pagination);

		// get the records for the current page
		$this->data['users'] = Model_User::query()->offset(\Pagination::$offset)->limit(\Pagination::$per_page)->get();

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Users');

		// and define the content body
		\Theme::instance()->set_partial('content', 'users/admin/users/index')->set($this->data);
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
			\Messages::error('User #'.$id.' does not exist.');
			\Messages::redirect('admin/users');
		}

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Users');

		// and define the content body
		\Theme::instance()->set_partial('content', 'users/admin/users/view')->set($this->data);
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
					\Messages::success('Added user #'.$id.'.');
					\Messages::redirect('admin/users');
				}
				catch (\Exception $e)
				{
					// else display an error
					\Messages::error($e->getMessage());
				}
			}
			else
			{
				// validation errors, show them
				foreach($val->error() as $e)
				{
					\Messages::error($e->get_message());
				}
			}
		}

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Users');

		// and define the content body
		\Theme::instance()->set_partial('content', 'users/admin/users/create')->set($this->data);
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
			\Messages::error('User #'.$id.' does not exist.');
			\Messages::redirect('admin/users');
		}

		// make sure the full_name profile field exists
		isset($user->profile_fields['full_name']) or $user->profile_fields['full_name'] = '';

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
				\Messages::success('Updated user #' . $id);
				\Messages::redirect('admin/users');
			}

			else
			{
				\Messages::error('Could not update user #' . $id);
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
				foreach($val->error() as $e)
				{
					\Messages::error($e->get_message());
				}
			}
		}

		// set the admin page title
		\Theme::instance()->get_template()->set('title', 'Users');

		// and define the content body
		\Theme::instance()->set_partial('content', 'users/admin/users/edit')->set($this->data)->set('user', $user, false);
	}

	/**
	 * Delete a user
	 */
	public function action_delete($id = null)
	{
		if ($user = Model_User::find($id))
		{
			$user->delete();

			\Messages::success('Deleted user #'.$id);
		}

		else
		{
			\Messages::error('Could not delete user #'.$id);
		}

		\Messages::redirect('users/admin');

	}

}
