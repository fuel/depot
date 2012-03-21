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

class Controller_Login extends \Controller_Base_Public
{
	/**
	 * Controller method preparations
	 *
	 * @return  void
	 */
	public function before()
	{
		// already logged in?
		if (\Auth::check())
		{
			\Messages::error('You are already logged in');
			\Response::redirect(\Input::post('redirect_to', '/'));
		}

		parent::before();

		// Load the ninjaauth configuration
		\Config::load('ninjauth', true);
	}

	/**
	 * The module index
	 *
	 * @return  Response
	 */
	public function action_index()
	{
		// create the form fieldset, do not add an {open}, a closing ul and a {close}, we have a custom form layout!
		$fieldset = \Fieldset::forge('login');
		$fieldset->add('username', 'Username', array('maxlength' => 50), array(array('required')))
			->add('password', 'Password', array('type' => 'password', 'maxlength' => 255), array(array('required'), array('min_length', 8)));

		// was the login form posted?
		if (\Input::post())
		{
			// deal with the login type
			switch (\Input::post('btnSubmit', false))
			{
				case 'Login':
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
						// create an Auth instance
						$auth = \Auth::instance();

						// check the credentials.
						if ($auth->login(\Input::param('username'), \Input::param('password')))
						{
							\Messages::success('You have logged in successfully');
							\Response::redirect(\Input::post('redirect_to', '/'));
						}
						else
						{
							\Messages::error('Username and/or password is incorrect');
						}
					}
					break;

				case 'Facebook':
					\Response::redirect('users/login/session/facebook');
					break;

				case 'Twitter':
					\Response::redirect('users/login/session/twitter');
					break;

				case 'Github':
					\Response::redirect('users/login/session/github');
					break;

				case 'Google+':
					\Response::redirect('users/login/session/google');
					break;
			}
		}

		// set the login page content partial
		\Theme::instance()->set_partial('content', 'users/login/index')->set('fieldset', $fieldset, false);
	}

	/**
	 * send the request to the selected provider
	 * to start the authentication session
	 */
	public function action_session($provider = null)
	{
		try
		{
			Strategy::forge($provider)->authenticate();
		}
		catch (\Exception $e)
		{
			\Messages::error($e->getMessage());
			\Response::redirect('users/login');
		}
	}

	/**
	 * handle the providers response
	 */
	public function action_callback($provider)
	{
		try
		{
			Strategy::login_or_register(Strategy::forge($provider));
		}
		catch (\Exception $e)
		{
			\Messages::error($e->getMessage());
			\Response::redirect('users/login');
		}
	}
}
