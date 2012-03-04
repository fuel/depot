<?php
/**
 * Part of Fuel Depot.
 *
 * Based on the message container from Cartalyst LLC
 * Licensed under the 3-clause BSD License.
 *
 * @package    FuelDepot
 * @version    1.0
 * @author     Cartalyst LLC
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2012 Fuel Development Team
 * @link       http://depot.fuelphp.com
 */

/**
 * Messages Application Interface
 */
class Messages
{
	/**
	 * default instance
	 *
	 * @var  array
	 */
	protected static $_instance = null;

	/**
	 * All the Asset instances
	 *
	 * @var  array
	 */
	protected static $_instances = array();

	/**
	 * Return a specific instance, or the default instance (is created if necessary)
	 *
	 * @param   string  instance name
	 * @return  Asset_Instance
	 */
	public static function instance($instance = null)
	{
		if ($instance !== null)
		{
			if ( ! array_key_exists($instance, static::$_instances))
			{
				return false;
			}

			return static::$_instances[$instance];
		}

		if (static::$_instance === null)
		{
			static::$_instance = static::forge();
		}

		return static::$_instance;
	}

	/**
	 * Gets a new instance of the Messages class.
	 *
	 * @param   string  instance name
	 * @return  Messages
	 */
	public static function forge($name = 'messages')
	{
		if ($exists = static::instance($name))
		{
			\Error::notice('Messages with this name exists already, cannot be overwritten.');
			return $exists;
		}

		static::$_instances[$name] = new \Messages_Instance($name);

		if ($name == 'messages')
		{
			static::$_instance = static::$_instances[$name];
		}

		return static::$_instances[$name];
	}

	/**
	 * You can not instantiate this class
	 *
	 * @return  void
	 */
	private function __construct()
	{
	}

	/**
	 * Adds an error message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public static function error($message)
	{
		return static::instance()->error($message);
	}

	/**
	 * Adds an info message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public static function info($message)
	{
		return static::instance()->info($message);
	}

	/**
	 * Adds a warning message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public static function warning($message)
	{
		return static::instance()->warning($message);
	}

	/**
	 * Adds a success message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public static function success($message)
	{
		return static::instance()->success($message);
	}

	/**
	 * Returns if there are any messages in the queue or not
	 *
	 * @return  bool
	 */
	public static function any()
	{
		return static::instance()->any();
	}
}
