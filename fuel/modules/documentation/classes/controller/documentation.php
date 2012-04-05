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
	 * The module index
	 *
	 * @access  public
	 * @return  Response
	 */
	public function router($method, $params)
	{
		// make sure our params have a value
		empty($params) or $params = \Arr::to_assoc($params);

		empty($params['version']) and $params['version'] = 0;
		empty($params['page']) and $params['page'] = 0;

		// store them
		$this->params = $params;

		// load the defined FuelPHP versions, ordered by version
		$versions = \DB::select()->from('versions')->order_by('major', 'ASC')->order_by('minor', 'ASC')->order_by('branch', 'ASC')->execute();

		// create the dropdown array
		$dropdown = array();
		foreach ($versions as $record)
		{
			$dropdown[$record['id']] = $record['major'].'.'.$record['minor'].'/'.$record['branch'];
		}

		// add the docs page partial to the template
		$partial = \Theme::instance()->set_partial('content', 'documentation/index');

		// was a specific page requested?
		if ($this->params['page'])
		{
			// find it, and load the latest docs page
			$doc = \Admin\Model_Doc::find()->related('page')->where('page_id', '=', $this->params['page'])->order_by('created_at', 'DESC')->get_one();

			if ($doc)
			{
				//set the fuelphp version so we can load the correct docs menu
				$this->params['version'] = $doc->page->version_id;

				try
				{
					// get the rendered page details from cache
					$details = \Cache::get('documentation.menutree.version_'.$this->params['version'].'.page_'.$this->params['page']);
				}
				catch (\CacheNotFoundException $e)
				{
					// not found, get it from the database, and render it
					$details = \Markdown::parse($doc->content);

					// cache the rendered result an hour if not in development, else only for 60 seconds
					\Cache::set('documentation.menutree.version_'.$this->params['version'].'.page_'.$this->params['page'], $details, \Fuel::$env == \Fuel::DEVELOPMENT ? 60 : 3600);
				}

				// some data about the last editor, and the time of the last edit
				$partial->set('pagedata', array('user' => $doc->page->user->profile_fields['full_name'], 'updated' => $doc->created_at));
			}
			else
			{
				// docs not found, but a page entry exists?
				$page = \Admin\Model_Page::find($this->params['page']);

				if ($page)
				{
					//set the version so we can load the correct menu version
					$this->params['version'] = $page->version_id;

					// and display a docs-not-present-yet message
					$details = \Theme::instance()->view('documentation/notfound');
					$partial->set('pagedata', false);
				}
				else
				{
					// unknown page request, redirect to the main docs page
					\Response::redirect('documentation');
				}
			}

			// set the page details on the partial
			$partial->set('details', $details, false);
		}
		else
		{
			// if no page details were found, see if we have a default page
			$page = \Admin\Model_Page::find()->where('version_id', '=', $this->params['version'])->where('default', '=', 1)->get_one();

			if ($page)
			{
				// found a default page, so redirect to it
				\Response::redirect('documentation/page/'.$page->id);
			}
			else
			{
				// is the requested version valid?
				if ($this->params['version'])
				{
					if ( ! array_key_exists($this->params['version'], $dropdown))
					{
						// unknown version request, redirect to the main docs page
						\Response::redirect('documentation');
					}

					$partial->set('details', \Theme::instance()->view('documentation/intro'));
					$partial->set('pagedata', false);
				}
				else
				{
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
					$partial = \Theme::instance()->set_partial('content', 'documentation/error', true);
					return;
				}
			}
		}

		// fetch the data for this version of the docs
		try
		{
			$menutree = \Cache::get('documentation.menutree.version_'.$this->params['version'].'.menu_'.$this->params['page']);
		}
		catch (\CacheNotFoundException $e)
		{
			$menutree = $this->fetchmenu();

			// cache for an hour if not in development, else only for 60 seconds
			\Cache::set('documentation.menutree.version_'.$this->params['version'].'.menu_'.$this->params['page'], $menutree, \Fuel::$env == \Fuel::DEVELOPMENT ? 60 : 3600);
		}

		// get the content partial, add the meju and the page details to it
		$partial->set('menutree', $menutree, false);

		// add the version dropdown and the selected version to it
		$partial->set(array('versions' => $dropdown, 'selection' => $this->params));

		// if in edit mode, add the edit menu's to the page
		$edittree = $editpage = '';
		if (\Auth::has_access('access.admin') or \Session::get('ninjauth.authentication.provider', false) == 'github')
		{
			$edittree = \Theme::instance()->view('documentation/edittree');
			$editpage = \Theme::instance()->view('documentation/editpage');
		}

		$partial->set('editpage', $editpage, false);
		$partial->set('edittree', $edittree, false);
	}

	protected function fetchmenu()
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

		return $menutree;
	}
}
