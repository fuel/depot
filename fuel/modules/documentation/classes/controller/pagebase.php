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

class Controller_Pagebase extends \Controller_Base_Public
{
	/*
	 * setup the documentation details page
	 */
	protected function buildpage($page)
	{
		// add the docs index page partial to the template
		$partial = \Theme::instance()->set_partial('content', 'documentation/index');

		// load the defined FuelPHP versions from the database, ordered by version
		$versions = \DB::select()
			->from('versions')
			->order_by('major', 'ASC')
			->order_by('minor', 'ASC')
			->order_by('created_at', 'ASC')
			->execute();

		// create the dropdown array
		$dropdown = array();
		foreach ($versions as $record)
		{
			$dropdown[$record['id']] = $record['major'].'.'.$record['minor'].'/'.$record['branch'];
		}

		// add the version dropdown and the selected version and page to the template partial
		$partial->set(array('versions' => $dropdown, 'selection' => array('version' => \Session::get('version'), 'page' => ($page ? $page->id : 0))));

		// get the number of page versions of the loaded page
		if ($page and $page->editable)
		{
			$doccount = Model_Doc::find()->where('page_id', '=', $page->id)->order_by('created_at', 'DESC')->count();
			$partial->set('doccount', $doccount)->set('page_id', $page->id)->set('pagedata', false)->set('function', \Uri::segment(2));
		}
		else
		{
			// no docs pages available for editing
			$partial->set('doccount', null)->set('page_id', 0)->set('pagedata', false)->set('function', \Uri::segment(2));
		}

		// add the docs menu to the docs index partial
		$partial->set('menutree', $this->buildmenu(($page ? $page->id : 0)), false);

		return $partial;
	}

	/*
	 * setup the documentation menu for this version
	 */
	protected function buildmenu($selected_page = 0)
	{
		// determine if we're in edit mode
		$editmode = ( \Auth::has_access('access.staff') or \Session::get('ninjauth.authentication.provider', false) == 'github');

		// fetch the menu tree for this version of the docs
		try
		{
			list($tree, $docs) = \Cache::get('documentation.version_'.\Session::get('version').'.menu');
		}
		catch (\CacheNotFoundException $e)
		{
			// load the tree for this version
			$model = Model_Page::forge()->tree_select(\Session::get('version'))->tree_get_root();

			// did we find it?
			if ($model)
			{
				// load all doc id's
				$docs = \DB::select()
					->select('page_id')
					->distinct()
					->from('docs')
					->execute()->as_array(null, 'page_id');

				// fetch the menu tree
				$tree = $model->tree_dump_as_array(array(), true, true);
			}
			else
			{
				// no pages for this version
				$tree = $docs = array();
			}

			// and cache for an hour if not in development, else only for 60 seconds
			\Cache::set('documentation.version_'.\Session::get('version').'.menu', array($tree, $docs), \Fuel::$env == \Fuel::DEVELOPMENT ? 60 : 3600);
		}

		// get the menu state cookie so we can restore state
		$state = explode(',', str_replace('#node_', '', \Cookie::get('depotmenustate', '')));

		// closure to generate an unordered list
		$menu = function ($nodes, $depth = 3, $close = false) use(&$menu, $docs, $selected_page, $editmode, $state)
		{
			// some storage for the result
			$output = '';

			// do we have any nodes?
			if ($nodes)
			{
				$result = '';

				// loop through the nodes
				foreach ($nodes as $node)
				{
					// does this node have children?
					if ($node['children'])
					{
						// and recurse to generate the unordered list for the children
						$submenu = $menu($node['children'], $depth+1, ! in_array($node['id'], $state));

						// add the node
						if (\Auth::has_access('access.staff'))
						{
							$result .= str_repeat("\t", $depth+1).'<li id="page_'.$node['id'].'" class="'.(in_array($node['id'], $state)?'minus"':'plus"').($close?' style="display:none;"':'').'><div><a href="/documentation/page/'.$node['id'].'">'.e($node['title']).'</a></div>'."\n".$submenu;
						}
						else
						{
							$result .= str_repeat("\t", $depth+1).'<li id="page_'.$node['id'].'" class="'.(in_array($node['id'], $state)?'minus"':'plus"').($close?' style="display:none;"':'').'><div>'.e($node['title']).'</div>'."\n".$submenu;
						}

					}
					else
					{
						// check if this page has any docs defined
						$has_docs = in_array($node['id'], $docs);

						// and add the link to the docs page
						$result .= str_repeat("\t", $depth+1).'<li id="page_'.$node['id'].'"'.($has_docs?' class="no-nest"':'').($close?' style="display:none;"':'').'><div'.($node['id']==$selected_page?' class="current"':'').'><a href="/documentation/page/'.$node['id'].'">'.e($node['title']).'</a></div>'."\n";
					}

					// close the node
					$result .= str_repeat("\t", $depth+1).'</li>'."\n";
				}

				// start the new unordered list
				if ($depth == 3)
				{
					$output .= str_repeat("\t", $depth).'<ul'.($editmode?' class="sortable"':'').'>'."\n";
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
	 *
	 * @return bool
	 */
	protected function checkaccess()
	{
		if ( ! \Auth::has_access('access.staff') and ! \Session::get('ninjauth.authentication.provider', false) == 'github')
		{
			// nope, inform the user and don't do anything
			\Messages::error('You don\'t have the requested rights to this page!');

			return false;
		}

		return true;
	}
}
