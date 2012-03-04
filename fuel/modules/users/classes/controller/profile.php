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

class Controller_Profile extends \Controller_Base_User
{
	/**
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// create the form fieldset, do not add an {open}, a closing ul and a {close}, we have a custom form layout!
		$fieldset = \Fieldset::forge('profile');
		$fieldset->add('full_name', 'Full name', array('maxlength' => 50), array(array('required')))
			->add('email', 'Email', array('maxlength' => 255), array(array('required'), array('valid_email')))
			->add('password', 'New password', array('type' => 'password', 'maxlength' => 255), array(array('min_length', 8)))
			->add('old_password', 'Current password', array('type' => 'password', 'maxlength' => 255), array(array('min_length', 8)))
			->add('btnSubmit', '', array('value' => 'Update', 'type' => 'submit', 'tag' => 'button'));

		// was the form posted?
		if (\Input::post('btnSubmit', false))
		{
			// validate the input
			// run the form validation
			if ( ! $fieldset->validation()->run())
			{
				// set any error messages we need to display
				foreach ($fieldset->validation()->error() as $error)
				{
					\Messages::error($error);
				}
			}
			else
			{
				// update email and fullname
				try
				{
					\Auth::update_user(array('email' => $fieldset->validated('email'), 'full_name' => $fieldset->validated('full_name')));
					\Messages::info('Profile information successfully updated');
				}
				catch (\Exception $e)
				{
					\Messages::error($e->getMessage());
				}

				// was a password change requested?
				if ( $fieldset->validated('password') and $fieldset->validated('old_password'))
				{
					try
					{
						\Auth::update_user(array('password' => $fieldset->validated('password'), 'old_password' => $fieldset->validated('old_password')));
						\Messages::info('Password succesfully updated');
					}
					catch (\Exception $e)
					{
						\Messages::error($e->getMessage());
					}
				}
			}
		}

		// populate the form
		$profile = \Auth::get_profile_fields();

		$fieldset->populate(array(
			'email' => \Input::post('email', \Auth::get_email()),
			'full_name' => \Input::post('full_name', $profile ? $profile['full_name'] : '')
		));

		// load the profile view
		$this->template->content = \View::forge('profile/index', array('fieldset' => $fieldset), false);
	}
}
