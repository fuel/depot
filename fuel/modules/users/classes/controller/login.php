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

/**
 * NinjAuth Controller
 *
 * @package    FuelPHP/NinjAuth
 * @category   Controller
 * @author     Phil Sturgeon
 * @copyright  (c) 2012 HappyNinjas Ltd
 * @license    http://philsturgeon.co.uk/code/dbad-license
 */

class Controller_Login extends \Controller_Base_Public
{
	public static $linked_redirect = '/';
	public static $login_redirect = '/';
	public static $register_redirect = '/users/register';
	public static $registered_redirect = '/users/profile';

	public function before()
	{
		// already logged in?
		if (\Auth::check())
		{
			\Messages::error('You are already logged in');
			\Response::redirect(\Input::post('redirect_to', '/'));
		}

		parent::before();

		// load the ninjauth package
		\Package::load('ninjauth');

		// Load the configuration for this provider
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

	public function action_session($provider)
	{
		$url = \NinjAuth\Strategy::forge($provider)->authenticate();

		\Response::redirect($url);
	}

	public function action_callback($provider)
	{
		try
		{
			// Whatever happens, we're sending somebody somewhere
			$status = \NinjAuth\Strategy::forge($provider)->login_or_register();

			// Stuff should go with each type of response
			switch ($status)
			{
				case 'linked':
					\Messages::success('You have linked '.$provider.' to your account.');
					$url = static::$linked_redirect;
				break;

				case 'logged_in':
					\Messages::success('You have logged in.');
					$url = static::$login_redirect;
				break;

				case 'registered':
					\Messages::success('You have logged in with your new account.');
					$url = static::$registered_redirect;
				break;

				case 'register':
					\Messages::info('Please fill in any missing details and add a password.');
					$url = static::$register_redirect;
				break;

				default:
					\Messages::error('Strategy::login_or_register() has come up with a result that we dont know how to handle.');
					$url = '/';
				break;
			}

			\Response::redirect($url);
		}

		catch (\NinjAuth\CancelException $e)
		{
			\Messages::error('It looks like you canceled your authorisation.');
			\Response::redirect('/users/login');
		}

		catch (\NinjAuth\ResponseException $e)
		{
			\Messages::error($e->getMessage());
			\Response::redirect('/users/login');
		}

		catch (\NinjAuth\AuthException $e)
		{
			\Messages::error($e->getMessage());
			\Response::redirect('/users/login');
		}
	}

	public function action_register()
	{
		$user_hash = \Session::get('ninjauth.user');
		$authentication = \Session::get('ninjauth.authentication');

		// Working with what?
		$strategy = \NinjAuth\Strategy::forge($authentication['provider']);

		$full_name = \Input::post('full_name') ?: \Arr::get($user_hash, 'name');
		$username = \Input::post('username') ?: \Arr::get($user_hash, 'nickname');
		$email = \Input::post('email') ?: \Arr::get($user_hash, 'email');
		$password = \Input::post('password');

		if ($username and $full_name and $email and $password)
		{
			$user_id = $strategy->adapter->create_user(array(
				'username' => $username,
				'email' => $email,
				'full_name' => $full_name,
				'password' => $password,
			));

			if ($user_id)
			{
				Model_Authentication::forge(array(
					'user_id' => $user_id,
					'provider' => $authentication['provider'],
					'uid' => $authentication['uid'],
					'access_token' => $authentication['access_token'],
					'secret' => $authentication['secret'],
					'refresh_token' => $authentication['refresh_token'],
					'expires' => $authentication['expires'],
					'created_at' => time(),
				))->save();

				\Response::redirect(static::$registered_redirect);
			}
		}

		return \View::forge('register', array(
			'user' => (object) compact('username', 'full_name', 'email', 'password')
		));
	}
}
