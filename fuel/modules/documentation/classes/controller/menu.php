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

namespace Documentation;

class Controller_Menu extends \Controller_Base_Public
{
	/**
	 * @var	array	parameters
	 */
	protected $params = array();

	/**
	 * @var	array	all objects of the version table
	 */
	protected $versions = array();

	/**
	 * @var	array	version dropdown
	 */
	protected $dropdown = array();

	public function __construct($request, $response)
	{
		// call the parent constructor
		parent::__construct($request, $response);

		// load the defined FuelPHP versions from the database, ordered by version
		$this->versions = \DB::select()
			->from('versions')
			->order_by('major', 'ASC')
			->order_by('minor', 'ASC')
			->order_by('created_at', 'ASC')
			->execute();

		// create the dropdown array
		foreach ($this->versions as $record)
		{
			$this->dropdown[$record['id']] = $record['major'].'.'.$record['minor'].'/'.$record['branch'];
		}
	}

	public function action_index($version = '0')
	{
		// validate the access
		$this->checkaccess();

		// do we have something posted?
		if (\Input::post())
		{
			if (\Input::post('back'))
			{
				// back button used
				\Response::redirect('documentation/version/'.$version);
			}
		}

		// store and unify the parameters
		$this->params = array('version' => $version, 'page' => '0');

		// is the requested version valid?
		if ( ! array_key_exists($this->params['version'], $this->dropdown))
		{
			// unknown version request, redirect to the main docs page
			\Response::redirect('documentation');
		}

		// add the docs index page partial to the template
		$partial = \Theme::instance()->set_partial('content', 'documentation/menuindex');

		// add the version dropdown and the selected version to it
		$partial->set(array('versions' => $this->dropdown, 'selection' => $this->params));

		// generate the menu structure
		$partial->set('menutree', $this->fetchmenu(), false);
	}

	/*
	 * check if the current user has access to the requested action
	 */
	protected function checkaccess()
	{
		if ( ! \Auth::has_access('access.staff') and ! \Session::get('ninjauth.authentication.provider', false) == 'github')
		{
			// nope, inform the user and don't do anything
			\Messages::error('You don\'t have access to this page!');

			\Response::redirect('documentation');
		}
	}

	/*
	 * load the menu structure for this docs version
	 */
	protected function fetchmenu()
	{
		// fetch the menu tree for this version of the docs
		try
		{
			$this->tree = \Cache::get('documentation.version_'.$this->params['version'].'.menu');
		}
		catch (\CacheNotFoundException $e)
		{
			// load the tree for this version
			$model = \Admin\Model_Page::forge()->tree_select($this->params['version'])->tree_get_root();

			// did we find it?
			$this->tree = $model ? $model->tree_dump_as_array(array(), true, true) : array();

			// and cache for an hour if not in development, else only for 60 seconds
			\Cache::set('documentation.version_'.$this->params['version'].'.menu', $this->tree, \Fuel::$env == \Fuel::DEVELOPMENT ? 60 : 3600);
		}

		// php < 5.4 fix, can't pass $this to a closure
		$params =& $this->params;

		// closure to generate an unordered list
		$menu = function ($nodes, $depth = 3, &$found = false) use(&$menu, $params)
		{
			// some storage for the result
			$output = '';

			// do we have any nodes?
			if ($nodes)
			{
				// reset the found flag
				$found = false;

				$result = '';

				// loop through the nodes
				foreach ($nodes as $node)
				{
					// does this node have children?
					if ($node['children'])
					{
						// and recurse to generate the unordered list for the children
						$submenu = $menu($node['children'], $depth+1, $found);

						// add the node
						$result .= str_repeat("\t", $depth+1).'<li id="node_'.$node['id'].'"><div>'.$node['title'].'</div>'."\n".$submenu;

					}
					else
					{
						// and add the link to the docs page
						$result .= str_repeat("\t", $depth+1).'<li id="node_'.$node['id'].'"><div>'.$node['title'].'</div>'."\n";
					}

					// close the node
					$result .= str_repeat("\t", $depth+1).'</li>'."\n";
				}

				// start the new unordered list
				if ($depth == 3)
				{
					$output .= str_repeat("\t", $depth).'<ul class="sortable">'."\n";
				}
				else
				{
					$output .= str_repeat("\t", $depth).'<ul>'."\n";
				}

				// close the list
				$output .= $result.str_repeat("\t", $depth).'</ul>'."\n";
			}

			// return the result
			return $output;
		};

		// convert the tree into an unordered list
		$menutree = '';

		foreach ($this->tree as $book)
		{
			// add a new book title
			$menutree .= "\t\t\t".'<h5>'.$book['title'].'</h5>'."\n";

			// generate the menu for this book
			$menutree .= $menu($book['children']);
		}

		// return the generated tree
		return $menutree;
	}
}
