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

class Controller_Documentation extends \Controller_Base_Public
{
	/**
	 * @var	array	parameters
	 */
	 protected $params = array();

	/**
	 * @var	array	version dropdown
	 */
	protected $dropdown = array();

	/**
	 * @var	object	loaded page object
	 */
	protected $page = null;

	/**
	 * @var	object	loaded doc object
	 */
	protected $doc = null;

	/**
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function router($method, $params)
	{
		// process the URI params
		if (! empty($params))
		{
			// we need an even number or elements
			count($params) % 2 == 0 or $params[] = null;

			// convert our URI params to an assoc array
			$params = \Arr::to_assoc($params);
		}

		// set some defaults for required parameters
		empty($params['version']) and $params['version'] = 0;
		empty($params['page']) and $params['page'] = 0;

		// and store them
		$this->params = $params;

		// load the defined FuelPHP versions from the database, ordered by version
		$versions = \DB::select()
			->from('versions')
			->order_by('major', 'ASC')
			->order_by('minor', 'ASC')
			->order_by('created_at', 'ASC')
			->execute();

		// create the dropdown array
		foreach ($versions as $record)
		{
			$this->dropdown[$record['id']] = $record['major'].'.'.$record['minor'].'/'.$record['branch'];
		}

		// was a specific page requested?
		if ($this->params['page'])
		{
			// find it, and load the latest docs page
			if ( ! $this->fetchpage())
			{
				// unknown page request, redirect to the main docs page
				\Response::redirect('documentation');
			}
		}

		// was it a version (switch) request?
		elseif ($this->params['version'])
		{
			// find it, and load the latest docs page
			if ( ! $this->fetchversion())
			{
				// unknown version request, redirect to the main docs page
				\Response::redirect('documentation');
			}
		}

		// no version, and no page?
		else
		{
			// do we have a version stored in the session?
			if ($version = \Session::get('version', false))
			{
				\Response::redirect('documentation/version/'.$version);
			}

			// find the default version
			foreach ($versions as $record)
			{
				if ($record['default'])
				{
					\Response::redirect('documentation/version/'.$record['id']);
				}
			}

			// get the latest if no default is defined
			if ($versions->count())
			{
				\Response::redirect('documentation/version/'.$record['id']);
			}

			// giving up, no versions found, show an error message
			\Theme::instance()->set_partial('content', 'documentation/error', true);
		}

		// add the version dropdown and the selected version to it
		if ($partial = \Theme::instance()->get_partial('content', 'documentation/index'))
		{
			$partial->set(array('versions' => $this->dropdown, 'selection' => $this->params));
		}
	}

	/*
	 */
	protected function new_node()
	{
		return 'NEW NODE';
	}

	/*
	 */
	protected function new_page()
	{
		return 'NEW PAGE';
	}

	/*
	 */
	protected function edit_page()
	{
		return 'EDIT PAGE';
	}

	/*
	 */
	protected function page_diff()
	{
		return 'PAGE DIFF';
	}

	/*
	 * load the requested page
	 */
	protected function fetchpage()
	{
		// fetch the requested page entry
		$this->page = \Admin\Model_Page::find($this->params['page']);

		// was the page found?
		if ( ! $this->page)
		{
			// nothing found, no more stuff to do here...
			return false;
		}

		// build the query for this page
		$query = \Admin\Model_Doc::find()->where('page_id', '=', $this->params['page'])->order_by('created_at', 'DESC');

		// store the number of versions of this page
		$versions = $query->count();

		// find the latest version of the requested docs page
		$this->doc = $query->get_one();

		// load the latest version of the docs for this page
		try
		{
			// get the rendered page details from cache
			$details = \Cache::get('documentation.version_'.$this->params['version'].'.page_'.$this->params['page']);

		}
		catch (\CacheNotFoundException $e)
		{
			// found it?
			if ($this->doc)
			{
				// not found, get it from the database, and render it
				$details = \Markdown::parse($this->doc->content);

				// our custom markdown transformations: page number links
				$details = preg_replace('~\@page\:(\d+)~', \Uri::create('/documentation/page/$1'), $details);

				// cache the rendered result an hour if not in development
				\Fuel::$env == \Fuel::DEVELOPMENT or \Cache::set('documentation.version_'.$this->params['version'].'.page_'.$this->params['page'], $details, 3600);
			}
		}

		// if we don't have page details, set the not-found message
		empty($details) and $details = \Theme::instance()->view('documentation/notfound');

		//set the fuelphp version so we can load the correct docs menu
		$this->params['version'] = $this->page->version_id;

		// add the docs index page partial to the template
		$partial = \Theme::instance()->set_partial('content', 'documentation/index');

		// set some data about the last editor, and the time of the last edit
		if ($this->doc)
		{
			$partial->set('pagedata', array('user' => $this->page->user->profile_fields['full_name'], 'updated' => $this->doc->created_at));
		}
		else
		{
			$partial->set('pagedata', false);
		}

		// set the doc count so we can show the correct buttons
		$partial->set('doccount', $versions);

		// add the docs menu to the docs index partial
		$partial->set('menutree', $this->fetchmenu(), false);

		// do we have a form posted?
		if ($form = \Input::post('form'))
		{
			// create a new page node in the tree
			$form == 'new' and $details = $this->new_node();

			// create a new docs page for the current node
			$form == 'create' and $details =  $this->new_page();

			if ($form == 'edit')
			{
				// edit the docs page for the current node
				\Input::post('edit') and $details = $this->edit_page();

				// edit the docs page for the current node
				\Input::post('diff') and $details = $this->page_diff();
			}
		}

		// set the page details
		$partial->set('details', $details, false);

		return true;
	}

	/*
	 * find the version, and load a default page if possible
	 */
	protected function fetchversion()
	{
		// is the requested version valid?
		if ( ! array_key_exists($this->params['version'], $this->dropdown))
		{
			// unknown version request, redirect to the main docs page
			\Response::redirect('documentation');
		}

		// store the version number in the session
		\Session::set('version', $this->params['version']);

		// see if we can find a default page for the requested version
		$this->page = \Admin\Model_Page::find()->where('version_id', '=', $this->params['version'])->where('default', '=', 1)->get_one();

		if ($this->page)
		{
			// found a default page, so redirect to it
			\Response::redirect('documentation/page/'.$this->page->id);
		}

		// version exists, but no pages

		// add the docs index page partial to the template
		$partial = \Theme::instance()->set_partial('content', 'documentation/index');

		// set in intro page
		$partial->set('details', \Theme::instance()->view('documentation/intro'));

		// no page data, no data to edit or create
		$partial->set('pagedata', false);

		// add the dcos menu to the docs index partial
		$partial->set('menutree', $this->fetchmenu(), false);

		return true;
	}

	/*
	 * load the menu structure for this docs version
	 */
	protected function fetchmenu()
	{
		// fetch the menu tree for this version of the docs
		try
		{
			$tree = \Cache::get('documentation.version_'.$this->params['version'].'.menu');
		}
		catch (\CacheNotFoundException $e)
		{
			// load the tree for this version
			$model = \Admin\Model_Page::forge()->tree_select($this->params['version'])->tree_get_root();

			// did we find it?
			$result = $model ? $model->tree_dump_as_array() : array();

			// convert the flat array into a multi-dimensional one
			$tree = array();

			// array to track nodes with children
			$tracker = array();

			foreach($result as $node)
			{
				// add an array for possible childeren of this node
				$node['children'] = array();

				// do we already track the parent?
				if (array_key_exists($node['_parent_'], $tracker))
				{
					// add the node as a child of the parent
					$tracker[$node['_parent_']]['children'][$node['id']] = $node;

					// and to the tracker
					$tracker[$node['id']] =& $tracker[$node['_parent_']]['children'][$node['id']];
				}
				else
				{
					// no, add it to the tree
					$tree[$node['id']] = $node;

					// and to the tracker
					$tracker[$node['id']] =& $tree[$node['id']];
				}
			}

			// and cache for an hour if not in development, else only for 60 seconds
			\Cache::set('documentation.version_'.$this->params['version'].'.menu', $tree, \Fuel::$env == \Fuel::DEVELOPMENT ? 60 : 3600);
		}

		// php < 5.4 fix, can't pass $this to a closure
		$params =& $this->params;

		// closure to generate an unordered list
		$menu = function ($nodes, $depth = 3) use(&$menu, $params)
		{
			// some storage for the result
			$output = '';

			// do we have any nodes?
			if ($nodes)
			{
				// start the new unordered list
				if ($depth == 3)
				{
					$output .= str_repeat("\t", $depth).'<ul class="menutree">'."\n";
				}
				else
				{
					$output .= str_repeat("\t", $depth).'<ul>'."\n";
				}

				// loop through the nodes
				foreach ($nodes as $node)
				{
					// does this node have children?
					if ($node['children'])
					{
						// add the link with an open button
						$output .= str_repeat("\t", $depth+1).'<li><a class="collapsed">'.$node['title'].'</a>'."\n";

						// and recurse to generate the unordered list for the children
						$output .= $menu($node['children'], $depth+1);
					}
					else
					{
						// determine the class for this node
						$class = ($node['id'] == $params['page'] ? 'current' : '');

						// and add the link to the docs page
						$output .= str_repeat("\t", $depth+1).'<li><a class="'.$class.'" href="'.\Uri::create('documentation/page/'.$node['id']).'">'.$node['title'].'</a>'."\n";
					}

					// close the node
					$output .= str_repeat("\t", $depth+1).'</li>'."\n";
				}

				// close the list
				$output .= str_repeat("\t", $depth).'</ul>'."\n";
			}

			// return the result
			return $output;
		};

		// convert the tree into an unordered list
		$menutree = '';

		foreach ($tree as $book)
		{
			// add a new book title
			$menutree .= "\t\t\t".'<h5>'.$book['title'].'</h5>'."\n";

			// generate the menu for this book
			$menutree .= $menu($book['children']);
		}

		// return the generated tree
		return $menutree;
	}

	/*
	 * load the menu structure for this docs version
	 */
	protected function fetchmenux()
	{
		// fetch the menu tree for this version of the docs
		try
		{
			$menutree = \Cache::get('documentation.menutree.version_'.$this->params['version'].'.menu_'.$this->params['page']);
		}
		catch (\CacheNotFoundException $e)
		{
			// storage for the docs menutree
			$menutree = '';

			// load the tree for this version
			$model = \Admin\Model_Page::forge()->tree_select($this->params['version'])->tree_get_root();

			// php < 5.4 fix, can't pass $this to a closure
			$params =& $this->params;

			// closure to generate an unordered list
			$ul = function ($parent, $depth = 3) use(&$ul, $params) {
				$output = '';
				// check if the parent has children
				if ($parent->tree_has_children())
				{
					if ($depth == 3)
					{
						$output .= str_repeat("\t", $depth).'<ul class="menutree">'."\n";
					}
					else
					{
						$output .= str_repeat("\t", $depth).'<ul>'."\n";
					}
					$children = $parent->tree_get_children();
					foreach ($children as $child)
					{
						if ($child->tree_has_children())
						{
							$output .= str_repeat("\t", $depth+1).'<li><a class="collapsed">'.$child->title.'</a>'."\n";
							$output .= $ul($child, $depth+1);
						}
						else
						{
							$class = ($child->id == $params['page'] ? 'current' : '');
							$output .= str_repeat("\t", $depth+1).'<li><a class="'.$class.'" href="'.\Uri::create('documentation/page/'.$child->id).'">'.$child->title.'</a>'."\n";
						}
						$output .= str_repeat("\t", $depth+1).'</li>'."\n";
					}
					$output .= str_repeat("\t", $depth).'</ul>'."\n";
				}

				return $output;
			};

			// do we have a tree? and does it have nodes?
			if ($model and $model->tree_has_children())
			{
				// get the defined books
				$books = $model->tree_get_children();

				// and load the tree for them
				foreach($books as $book)
				{
					// book title
					$menutree .= "\t\t\t".'<h5>'.$book->title.'</h5>'."\n";

					// generate the menu
					$menutree .= $ul($book);
				}
			}

			// and cache for an hour if not in development, else only for 60 seconds
			\Cache::set('documentation.menutree.version_'.$this->params['version'].'.menu_'.$this->params['page'], $menutree, \Fuel::$env == \Fuel::DEVELOPMENT ? 60 : 3600);
		}

		return $menutree;
	}
}
