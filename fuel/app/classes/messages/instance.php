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
 * Messages Container
 */
class Messages_Instance implements ArrayAccess, Iterator
{
	/**
	 * @var  array  $messages  All of the messages
	 */
	protected $messages = array();

	/**
	 * @var  string  $instance  Name of the current instance
	 */
	protected $instance = null;

	/**
	 * Loads in all the messages from flash.
	 *
	 * @return  void
	 */
	public function __construct($name = 'messages')
	{
		$this->instance = $name;

		$this->messages = \Session::get_flash($this->instance, array());

		// register a shutdown event to write messages to flash
		\Event::register('shutdown', array($this, 'shutdown'), true);
	}

	/**
	 * Stores the currently loaded messages in flash
	 *
	 * @return  void
	 */
	public function shutdown()
	{
		\Session::set_flash($this->instance, $this->messages);
	}

	/**
	 * Adds an error message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public function error($message)
	{
		return $this->add_message('error', $message);
	}

	/**
	 * Adds an info message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public function info($message)
	{
		return $this->add_message('info', $message);
	}

	/**
	 * Adds a warning message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public function warning($message)
	{
		return $this->add_message('warning', $message);
	}

	/**
	 * Adds a success message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public function success($message)
	{
		return $this->add_message('success', $message);
	}

	/**
	 * Resets the message store
	 *
	 * @return  $this
	 */
	public function reset()
	{
		$this->messages = array();

		return $this;
	}

	/**
	 * Keep existing messages in the store
	 *
	 * @return  $this
	 */
	public function keep()
	{
		$this->messages = array_merge(\Session::get_flash($this->instance, array()), $this->messages);

		return $this;
	}

	/**
	 * Returns if there are any messages in the queue or not
	 *
	 * @return  bool
	 */
	public function any()
	{
		return (bool) count($this->messages);
	}

	/**
	 * Get all messsages of a given type, or all if no type was given
	 *
	 * @return  array
	 */
	public function get($type = null)
	{
		$messages = array();

		foreach($this->messages as $message)
		{
			if ($type === null or $message['type'] == $type)
			{
				$messages[] = $message;
			}
		}

		return $messages;
	}

	/**
	 * Message aware alias for Response::redirect.
	 * Saves stored messages before redirecting.
	 */
	public function redirect($url = '', $method = 'location', $code = 302)
	{
		$this->keep();
		\Response::redirect($url, $method, $code);
	}

	/**
	 * Adds a message of the given type
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	protected function add_message($type, $message)
	{
		is_array($message) or $message = array($message);

		foreach ($message as $msg)
		{
			// deal with validation errors passed as-is
			if ($msg instanceOf Validation_Error)
			{
				$msg = $msg->get_message();
			}

			array_push($this->messages, array(
				'type' => $type,
				'body' => $msg
			));
		}

		return $this;
	}


	/**
	 * Iterator - Rewind the info array to the first element
	 *
	 * @return  void
	 */
	public function rewind()
	{
		reset($this->messages);
	}

	/**
	 * Iterator - Return the current element of the info array
	 *
	 * @return  mixed
	 */
	public function current()
	{
		return current($this->messages);
	}

	/**
	 * Iterator - Return the key of the current element of the info array
	 *
	 * @return  mixed
	 */
	public function key()
	{
		return key($this->messages);
	}

	/**
	 * Iterator - Move forward to next element of the info array
	 *
	 * @return  mixed
	 */
	public function next()
	{
		return next($this->messages);
	}

	/**
	 * Iterator - Checks if current position is valid
	 *
	 * @return  bool
	 */
	public function valid()
	{
		return key($this->messages) !== null;
	}

	/**
	 * ArrayAccess - Sets the given message.
	 *
	 * @param   string  $offset  Offset to set
	 * @param   mixed   $value   Value to set
	 * @return  void
	 */
	public function offsetSet($offset, $value)
	{
		$this->messages[$offset] = $value;
	}

	/**
	 * ArrayAccess - Checks if the given message exists.
	 *
	 * @param   string  $offset  Offset to check
	 * @return  bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->messages[$offset]);
	}

	/**
	 * ArrayAccess - Unsets the given message.
	 *
	 * @param   string  $offset  Offset to set
	 * @return  void
	 */
	public function offsetUnset($offset)
	{
		unset($this->messages[$offset]);
	}

	/**
	 * ArrayAccess - Gets the given message.
	 *
	 * @param   string  $offset  Key
	 * @return  mixed
	 */
	public function offsetGet($offset)
	{
		return isset($this->messages[$offset]) ? $this->messages[$offset] : null;
	}
}
