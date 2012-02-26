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

namespace Api;

class Controller_Api extends \Controller_Base_Public
{
	/**
	 * @var	array	selected fuelphp version
	 */
	 protected $version = array();

	/**
	 * @var	string	selected file hash
	 */
	 protected $hash = null;

	/**
	 * Router
	 *
	 * Capture all calls, and use the URI segments to determine the page view
	 *
	 * @access  public
	 * @return  Response
	 */
	public function router($method, $params)
	{
		// make sure our params have a value
		isset($params[0]) or $params[0] = 0;
		isset($params[1]) or $params[1] = '';

		// set the selected hash
		$this->hash = $params[1];

		// set the template
		$this->template->content = \View::forge('api/index');

		// load the defined FuelPHP versions, ordered by version
		$result = \DB::select()->from('versions')->order_by('major', 'ASC')->order_by('minor', 'ASC')->order_by('branch', 'ASC')->execute();

		// create the dropdown array
		$dropdown = array();
		foreach ($result as $record)
		{
			$dropdown[$record['id']] = $record['major'].'.'.$record['minor'].'/'.$record['branch'];
		}

		// pass the dropdown to the view and set the selected version
		$this->template->content->set('versions', $dropdown);
		$this->template->content->set('version', $params[0]);

		// find the selected version by id match
		foreach ($result as $record)
		{
			if ($record['id'] == $params[0])
			{
				$this->version = $record;
				break;
			}
		}

		// if not found, get the default one
		if ( ! $this->version)
		{
			foreach ($result as $record)
			{
				if ($record['default'] == 1)
				{
					$this->version = $record;
					break;
				}
			}
		}

		// if not found, get the last one
		if ( ! $this->version and count($result) > 0 and ! empty($record))
		{
			$this->version = $record;
		}

		// still if not found, give up!
		if ( ! $this->version)
		{
			$this->template->content = \View::forge('api/error');
		}

		if ($this->version)
		{
			// if no version was selected using the dropdown, select the default
			$params[0] == 0 and \Response::redirect('api/'.$this->version['id']);

			// render the docs of the selected version
			$this->process();
		}

		// render the template to deal with asset timing issues due to late rendering
		$this->template->set('content', $this->template->content->render(), false);
	}

	/*
	 */
	protected function process()
	{
		// storage for the detailed api docs
		$details = '';

		// get the list of files with functions
		$result = \DB::select()
			->from('docblox')
			->where('version_id', $this->version['id'])
			->order_by('package', 'ASC')
			->order_by('file', 'ASC')
			->execute();

		// define the lists
		$constantlist = array();
		$functionlist = array();
		$classlist = array();

		foreach($result as $record)
		{
			// make sure we have a package name
			empty($record['package']) and $record['package'] = 'Undefined';
			$package = '<a class="collapsed">'.$record['package'].'</a>';

			// process any constants defined in this file
			if ($record['constants'] !== 'a:0:{}')
			{
				if (isset($constantlist[$package]))
				{
					$constantlist[$package] = array_merge($constantlist[$package], $this->get_constants($record));
				}
				else
				{
					$constantlist[$package] = $this->get_constants($record);
				}
			}

			// process any functions defined in this file
			if ($record['functions'] !== 'a:0:{}')
			{
				if (isset($functionlist[$package]))
				{
					$functionlist[$package] = array_merge($functionlist[$package], $this->get_functions($record));
				}
				else
				{
					$functionlist[$package] = $this->get_functions($record);
				}
			}

			// process any classes defined in this file
			if ($record['classes'] !== 'a:0:{}')
			{
				if (isset($classlist[$package]))
				{
					$classlist[$package] = array_merge($classlist[$package], $this->get_classes($record));
				}
				else
				{
					$classlist[$package] = $this->get_classes($record);
				}
			}

			// need details of this one?
			if ($this->hash == $record['hash'] and empty($details))
			{
				// unserialize all arrays
				is_array($record['docblock']) or $record['docblock'] = unserialize($record['docblock']);
				is_array($record['markers']) or $record['markers'] = unserialize($record['markers']);
				is_array($record['constants']) or $record['constants'] = unserialize($record['constants']);
				is_array($record['functions']) or $record['functions'] = unserialize($record['functions']);
				is_array($record['classes']) or $record['classes'] = unserialize($record['classes']);

	//\Debug::dump($record);die();
				// create the API details view
				$details = \View::forge('api/api', array('record' => $record));
			}
		}

		// add the lists to the template
		$this->template->content->set('constantlist', $constantlist, false);
		$this->template->content->set('functionlist', $functionlist, false);
		$this->template->content->set('classlist', $classlist, false);

		// if no api details were selected, show the intro page
		empty($details) and $details = \View::forge('api/intro');
		$this->template->content->set('details', $details);
	}

	/**
	 */
	protected function get_constants($record)
	{
		// storage for the result
		$result = array();

		// get the constants array
		$record['constants'] = unserialize($record['constants']);
		// normalize it
		isset($record['constants'][0]) or $record['constants'] = array($record['constants']);

		// loop through them
		foreach ($record['constants'] as $constant)
		{

			if ($this->hash == $record['hash'])
			{
				$result[] = \Html::anchor('api/'.$this->version['id'].'/'.$record['hash'], $constant['name'], array('class' => 'current'));
			}
			else
			{
				$result[] = \Html::anchor('api/'.$this->version['id'].'/'.$record['hash'], $constant['name']);
			}
		}

		// return the result
		return $result;
	}

	/**
	 */
	protected function get_functions($record)
	{
		// storage for the result
		$result = array();

		// get the functions array
		$record['functions'] = unserialize($record['functions']);

		// normalize it
		isset($record['functions'][0]) or $record['functions'] = array($record['functions']);

		// loop through them
		foreach ($record['functions'] as $function)
		{
			if ($this->hash == $record['hash'])
			{
				$result[] = \Html::anchor('api/'.$this->version['id'].'/'.$record['hash'], $function['name'], array('class' => 'current'));
			}
			else
			{
				$result[] = \Html::anchor('api/'.$this->version['id'].'/'.$record['hash'], $function['name']);
			}
		}

		// return the result
		return $result;
	}

	/**
	 */
	protected function get_classes($record)
	{
		// storage for the result
		$result = array();

		// get the classes array
		$record['classes'] = unserialize($record['classes']);

		// normalize it
		isset($record['classes'][0]) or $record['classes'] = array($record['classes']);

		// loop through them
		foreach ($record['classes'] as $class)
		{
			if ( ! empty($class))
			{
			if ($this->hash == $record['hash'])
			{
				$result[] = \Html::anchor('api/'.$this->version['id'].'/'.$record['hash'], $class['name'], array('class' => 'current'));
			}
			else
			{
				$result[] = \Html::anchor('api/'.$this->version['id'].'/'.$record['hash'], $class['name']);
			}
			}
		}

		// return the result
		return $result;
	}
}
