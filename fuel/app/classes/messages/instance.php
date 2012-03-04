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
	 * Loads in all the messages from flash.
	 *
	 * @return  void
	 */
	public function __construct($name = 'messages')
	{
		$this->messages = \Session::get_flash($name, array());
	}

	/**
	 * Adds an error message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public function error($message)
	{
		$this->add_message('error', $message);

		return $this;
	}

	/**
	 * Adds an info message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public function info($message)
	{
		$this->add_message('info', $message);

		return $this;
	}

	/**
	 * Adds a warning message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public function warning($message)
	{
		$this->add_message('warning', $message);

		return $this;
	}

	/**
	 * Adds a success message
	 *
	 * @param   string  $message  Message to add
	 * @return  $this
	 */
	public function success($message)
	{
		$this->add_message('success', $message);

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

		\Session::set_flash('messages', $this->messages);
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
