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
	public function action_index()
	{
		$data['users'] = Model_User::find('all');
		$this->template->title = "Users";
		$this->template->content = \View::forge('users/index', $data);

	}

	public function action_view($id = null)
	{
		$data['user'] = Model_User::find($id);

		$this->template->title = "User";
		$this->template->content = \View::forge('users/view', $data);

	}

	public function action_create($id = null)
	{
		if (\Input::method() == 'POST')
		{
			$val = Model_User::validate('create');

			if ($val->run())
			{
				$user = Model_User::forge(array(
					'username' => \Input::post('username'),
				));

				if ($user and $user->save())
				{
					\Session::set_flash('success', 'Added user #'.$user->id.'.');
					\Response::redirect('admin/users');
				}

				else
				{
					\Session::set_flash('error', 'Could not save user.');
				}
			}
			else
			{
				\Session::set_flash('error', $val->show_errors());
			}
		}

		$this->template->title = "Users";
		$this->template->content = \View::forge('users/create');

	}

	public function action_edit($id = null)
	{
		$user = Model_User::find($id);
		$val = Model_User::validate('edit');

		if ($val->run())
		{
			$user->username = \Input::post('username');

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
			if (\Input::method() == 'POST')
			{
				$user->username = $val->validated('username');

				\Session::set_flash('error', $val->show_errors());
			}

			$this->template->set_global('user', $user, false);
		}

		$this->template->title = "Users";
		$this->template->content = \View::forge('users/edit');

	}

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
