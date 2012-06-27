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

class Controller_Register extends \Controller_Base_Public
{
	/**
	 * Controller method preparations
	 *
	 * @return  void
	 */
	public function before()
	{
		parent::before();

		// load the ninjauth package
		\Package::load('ninjauth');

		// Load the ninjaauth configuration
		\Config::load('ninjauth', true);
	}

	/**
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// create the timezone list
		static $regions = array(
			'Africa' => \DateTimeZone::AFRICA,
			'America' => \DateTimeZone::AMERICA,
			'Antarctica' => \DateTimeZone::ANTARCTICA,
			'Asia' => \DateTimeZone::ASIA,
			'Atlantic' => \DateTimeZone::ATLANTIC,
			'Europe' => \DateTimeZone::EUROPE,
			'Indian' => \DateTimeZone::INDIAN,
			'Pacific' => \DateTimeZone::PACIFIC
		);

		$tzlist = array();
		foreach ($regions as $name => $mask) {
			foreach(\DateTimeZone::listIdentifiers($mask) as $tz)
			{
				$tzlist[$name][$tz] = $tz;
			}
		}

		// create the form fieldset, do not add an {open}, a closing ul and a {close}, we have a custom form layout!
		$fieldset = \Fieldset::forge('register');
		$fieldset->add('username', 'Username', array('maxlength' => 50), array(array('required')))
			->add('full_name', 'Password', array('maxlength' => 50), array(array('required')))
			->add('email', 'Email', array('maxlength' => 255), array(array('required'), array('valid_email')))
			->add('timezone', 'Timezone', array('type' => 'select', 'options' => $tzlist, 'value' => 'Europe/London'), array(array('required')))
			->add('dateformat', 'Date Format', array('type' => 'select', 'options' => array('eu' => 'European', 'us' => 'American')), array(array('required')))
			->add('password', 'Password', array('type' => 'password', 'maxlength' => 255), array(array('required'), array('min_length', 8)))
			->add('btnSubmit', '', array('value' => 'Register', 'type' => 'submit', 'tag' => 'button'));

		// see if we have a registration via a third-party provider
		$user_hash = \Session::get('ninjauth.user', false);

		// if it was a registration from the third-party provider, populate $_POST with the info retrieved
		if ($user_hash and ! \Input::post())
		{
			$_POST['full_name'] = \Arr::get($user_hash, 'name');
			$_POST['username'] = \Arr::get($user_hash, 'nickname');
			$_POST['email'] = \Arr::get($user_hash, 'email');
		}

		// do we have post information
		if (\Input::post('btnSubmit', false))
		{
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
				// register the new user
				try
				{
					$user_id = \Auth::create_user(
						\Input::post('username'),
						\Input::post('password'),
						\Input::post('email'),
						\Config::get('ninjauth.default_group'),
						array(
							'full_name' => \Input::post('full_name'),
							'dateformat' => \Input::post('dateformat'),
							'timezone' => \Input::post('timezone'),
						)
					);

					if ($user_id and $user_hash)
					{
						$authentication = \Session::get('ninjauth.authentication', false);

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
					}

					\Response::redirect(\Config::get('ninjauth.urls.registered'));
				}
				catch (\Auth\SimpleUserUpdateException $e)
				{
					\Messages::error($e->getMessage());
				}
			}
		}
		elseif ($user_hash)
		{
				\Messages::info('Please fill in the missing details');
		}

		$fieldset->populate(\Input::post());

		// set the profile view content partial
		\Theme::instance()->set_partial('content', 'users/register/index')->set('fieldset', $fieldset, false);
	}

}
