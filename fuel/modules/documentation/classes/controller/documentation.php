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
	 * @var	array	all objects of the version table
	 */
	protected $versions = array();

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
	 * @var	array	loaded menu tree structure
	 */
	protected $tree = array();

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

	/**
	 * Documentation start page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// do we have a version stored in the session?
		if ($version = \Session::get('version', false))
		{
			\Response::redirect('documentation/version/'.$version);
		}

		// find the default version
		foreach ($this->versions as $record)
		{
			if ($record['default'])
			{
				\Response::redirect('documentation/version/'.$record['id']);
			}
		}

		// get the latest if no default is defined
		if ($this->versions->count())
		{
			\Response::redirect('documentation/version/'.$record['id']);
		}

		// giving up, no versions found, show an error message
		\Theme::instance()->set_partial('content', 'documentation/error', true);
	}

	/**
	 * Select a version of FuelPHP
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_version($version  = '0')
	{
		// store and unify the parameters
		$this->params = array('version' => $version, 'page' => '0');

		// delete any stored version in the session
		\Session::delete('version');

		// find it, and load the latest docs page
		if ( ! $this->fetchversion())
		{
			// unknown version request, redirect to the main docs page
			\Response::redirect('documentation');
		}
	}

	/**
	 * Load a specific documentation page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_page($page = '0')
	{
		// store and unify the parameters
		$this->params = array('version' => '0', 'page' => $page);

		// find it, and load the latest docs page
		if ( ! $this->fetchpage())
		{
			// unknown page request, redirect to the main docs page
			\Response::redirect('documentation');
		}
	}

	/**
	 * Load the menu editor
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_menu($version = '0')
	{
		// validate the access
		$this->checkaccess();

		// store and unify the parameters
		$this->params = array('version' => $version, 'page' => '0');

		// build the page layout partial
		$partial = $this->buildpage();

		$partial->set('details', 'Edit the menu for this version');
	}

	/**
	 * Load the page editor
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_edit($page = '0')
	{
		// validate the access
		$this->checkaccess();

		// store and unify the parameters
		$this->params = array('version' => '0', 'page' => $page);

		// fetch the requested page
		$this->page = \Admin\Model_Page::find($this->params['page']);

		// found?
		if ( ! $this->page)
		{
			// unknown page request, redirect to the main docs page
			\Response::redirect('documentation');
		}

		// set the version from the page so the tree can be generated
		$this->params['version'] = $this->page->version_id;

		// get the docs page to edit
		$this->doc = \Admin\Model_Doc::find()->where('page_id', '=', $this->params['page'])->order_by('created_at', 'DESC')->get_one();

		// build the page layout partial
		$partial = $this->buildpage();

		// add the edit page partial
		$details = \Theme::instance()->view('documentation/editpage');

		// do we have something posted?
		if (\Input::post('page', false) !== false)
		{
			if (\Input::post('cancel'))
			{
				// cancel button used
				\Response::redirect('documentation/page/'.$this->page->id);
			}

			elseif (\Input::post('submit'))
			{
				// create the validation object
				$val = \Validation::forge('editpage');

				// set the validation rules
				$val->add('title', 'Title')->add_rule('required');
				$val->add('slug', 'Slug')->add_rule('required');
				$val->add('page', 'Page')->add_rule('required');

				if ($val->run())
				{
					// save the changes to the page
					$this->page->title = $val->validated('title');
					$this->page->slug = $val->validated('slug');

					// anything changed
					if ($this->page->is_changed())
					{
						\Cache::delete('documentation.version_'.$this->page->version_id.'.menu');
					}

					// and save it
					$this->page->save();

					// any changes to the page
					if ($this->doc->content == $val->validated('page'))
					{
						// nope, inform the user and don't do anything
						\Messages::warning('No changes were made to the page');
					}
					else
					{
						// get the user id
						$user = \Auth::get_user_id();
						// create a new docs page
						$this->doc = \Admin\Model_Doc::forge(array(
							'page_id' => $this->page->id,
							'user_id' => $user[1],
							'content' => $val->validated('page'),
						));
						$this->doc->save();

						// delete the page cache
						\Cache::delete('documentation.version_'.$this->page->version_id.'.page_'.$this->page->id);

						// nope, inform the user and don't do anything
						\Messages::success('Page successfully saved!');
					}

					// and return to the page
					\Response::redirect('documentation/page/'.$this->page->id);
				}

				// set any error messages we need to display
				\Messages::error($val->error());

				// set the page variables on the view
				$details
					->set('title', \Input::post('title'))
					->set('slug', \Input::post('slug'))
					->set('page', \Input::post('page'));
			}

			elseif (\Input::post('preview'))
			{
				// set the page variables on the view
				$details
					->set('title', \Input::post('title'))
					->set('slug', \Input::post('slug'))
					->set('page', \Input::post('page'))
					->set('preview', $this->renderpage(htmlentities(\Input::post('page'))), false);
			}
		}
		else
		{
			// set the page variables on the view
			$details
				->set('title', $this->page->title)
				->set('slug', $this->page->slug)
				->set('page', $this->doc ? $this->doc->content : '')
				->set('preview', false);
		}

		// and set the partial
		$partial->set('details', $details);
	}

	/**
	 * Load the diff viewer
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_diff($page = '0')
	{
		// validate the access
		$this->checkaccess();

		// store and unify the parameters
		$this->params = array('version' => '0', 'page' => $page);

		// get the docs pages
		$docs = \Admin\Model_Doc::find()->where('page_id', '=', $this->params['page'])->order_by('created_at', 'DESC')->get();

		// did we find more then one?
		if ( ! $docs or count($docs) < 2)
		{
			// nope, inform the user there's nothing to diff
			\Messages::error('No page versions available to run a diff on!');

			// and return to the page
			\Response::redirect('documentation/page/'.$page);
		}

		// store the version id too, we need it to generate the menu
		$this->params['version'] = reset($docs)->page->version_id;

		// build the page layout partial
		$partial = $this->buildpage();

		// do we have something posted?
		if (\Input::post('cancel'))
		{
			// cancel button used
			\Response::redirect('documentation/page/'.reset($docs)->page_id);
		}

		elseif (\Input::post('delete') and \Auth::has_access('access.staff'))
		{
			if ($selected = \Input::post('selected', false))
			{
				// delete the page cache
				\Cache::delete('documentation.version_'.reset($docs)->page->version_id.'.page_'.reset($docs)->page->id);

				foreach ($selected as $doc)
				{
					if (isset($docs[$doc]))
					{
						$docs[$doc]->delete();
					}
				}

				// nothing selected to delete
				\Messages::success('Selected page versions were succesfully deleted!');
			}
			else
			{
				// nothing selected to delete
				\Messages::warning('No page versions were selected!');
			}

			// and return to the diff page
			\Response::redirect('documentation/diff/'.reset($docs)->page_id);
		}

		elseif (\Input::post('view'))
		{
			// do we have valid input?
			if (\Input::post('before') and \Input::post('after'))
			{
				if (\Input::post('before') == \Input::post('after'))
				{
					// nope, inform the user there's nothing to diff
					\Messages::error('No point comparing a version with itself!');

					// invalid input, try again
					\Response::redirect('documentation/diff/'.reset($docs)->page_id);
				}
				else
				{
					if (isset($docs[\Input::post('before')]) and isset($docs[\Input::post('after')]))
					{
						// load the diff class
						require_once APPPATH.'vendor'.DS.'finediff'.DS.'finediff.php';

						// add the view diff partial
						$details = \Theme::instance()->view('documentation/viewdiff');

						// run the diff on the two versions
						$opcodes = \FineDiff::getDiffOpcodes($docs[\Input::post('after')]->content, $docs[\Input::post('before')]->content);
						$details->set('diff', $this->renderpage(\FineDiff::renderDiffToMarkdownFromOpcodes($docs[\Input::post('after')]->content, $opcodes)), false);

						$details->set('before', \Input::post('before'));
						$details->set('after', \Input::post('after'));
					}
					else
					{
						// nope, inform the user there's nothing to diff
						\Messages::error('Invalid page versions selected to run a diff on!');

						// invalid input, try again
						\Response::redirect('documentation/diff/'.reset($docs)->page_id);
					}
				}
			}
			else
			{
				// nope, inform the user there's nothing to diff
				\Messages::error('No page versions selected to run a diff on!');

				// invalid input, try again
				\Response::redirect('documentation/diff/'.reset($docs)->page_id);
			}
		}

		else
		{
			// add the view diff partial
			$details = \Theme::instance()->view('documentation/diffindex');

			// pass the docs to it
			$details->set('docs', $docs);
		}

		// and set the partial
		$partial->set('details', $details);
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
				// render the page
				$details = $this->renderpage(htmlentities($this->doc->content));

				// cache the rendered result an hour if not in development
				\Cache::set('documentation.version_'.$this->page->version_id.'.page_'.$this->page->id, $details, 3600);
			}
		}

		// if we don't have page details, set the not-found message
		empty($details) and $details = \Theme::instance()->view('documentation/notfound');

		//set the fuelphp version so we can load the correct docs menu
		$this->params['version'] = $this->page->version_id;

		// build the page layout partial
		$partial = $this->buildpage();

		// set some data about the last editor, and the time of the last edit
		if ($this->doc)
		{
			$partial->set('pagedata', array('user' => $this->page->user->profile_fields['full_name'],'updated' => $this->doc->created_at, 'format' => $this->date_format));
		}
		else
		{
			$partial->set('pagedata', false);
		}

		// set the doc count so we can show the correct buttons
		$partial->set('doccount', $versions);

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

		// no default page exists for this version. Find the first page node
		$this->page = \Admin\Model_Page::find()->where('version_id', '=', $this->params['version'])->where('left_id', '>', 2)->where('right_id', '=', \DB::expr('left_id + 1'))->get_one();

		if ($this->page)
		{
			// found a page node, so redirect to it
			\Response::redirect('documentation/page/'.$this->page->id);
		}

		// build the page layout partial
		$partial = $this->buildpage();

		// set in intro page
		$partial->set('details', \Theme::instance()->view('documentation/intro'));

		// no page data, no data to edit or create
		$partial->set('pagedata', false);

		// no doc count either, so no create or edit buttons
		$partial->set('doccount', false);

		// add the docs menu to the docs index partial
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

						// add the link with an open button
						$result .= str_repeat("\t", $depth+1).'<li><a class="'.($found?'expanded':'collapsed').'">'.$node['title'].'</a>'."\n".$submenu;

					}
					else
					{
						// determine the class for this node
						if ($node['id'] == $params['page'])
						{
							$found = true;
							$class = 'current';
						}
						else
						{
							$class = '';
						}

						// and add the link to the docs page
						$result .= str_repeat("\t", $depth+1).'<li><a class="'.$class.'" href="'.\Uri::create('documentation/page/'.$node['id']).'">'.$node['title'].'</a>'."\n";
					}

					// close the node
					$result .= str_repeat("\t", $depth+1).'</li>'."\n";
				}

				// start the new unordered list
				if ($depth == 3)
				{
					$output .= str_repeat("\t", $depth).'<ul class="menutree">'."\n";
				}
				else
				{
					$output .= str_repeat("\t", $depth).'<ul '.($found?'style="display:block;"':'').'>'."\n";
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

	/*
	 * setup the documentation details page
	 */
	protected function buildpage()
	{
		// add the docs index page partial to the template
		$partial = \Theme::instance()->set_partial('content', 'documentation/index');

		// add the version dropdown and the selected version to it
		$partial->set(array('versions' => $this->dropdown, 'selection' => $this->params));

		// add the docs menu to the docs index partial
		$partial->set('menutree', $this->fetchmenu(), false);

		// set a default for the  doccount and the pagedata
		$partial->set('doccount', false)->set('pagedata', false);

		return $partial;
	}

	/*
	 * render the markdown
	 */
	protected function renderpage($doc = '')
	{

		// not found, get it from the database, and render it
		$details = \Markdown::parse($doc);

		// our custom markdown transformations: page number links
		$details = preg_replace('~\@page\:(\d+)~', \Uri::create('/documentation/page/$1'), $details);

		return $details;
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

}
