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

class Controller_Packages extends Controller_Apibase
{
	/**
	 * API version index page, selection via packages
	 */
	public function action_index($file = null)
	{
		// get the index page partial
		$partial = $this->buildpage();

		// if no file was requested
		if ( ! $file)
		{
			// fetch the first one we can find
			$model = \DB::select('id', 'package', 'hash', 'file')
				->from('docblox')
				->where('version_id', '=', \Session::get('version', 0))
				->order_by('package', 'ASC')
				->order_by('file', 'ASC')
				->limit(1)
				->execute();

			// and did we?
			$model->count() and \Messages::redirect('api/packages/'.$model[0]['hash']);
		}

		// add the packages menu to the page partial
		$partial->set('menutree', $this->buildmenu($file), false);

		// add the packages menu to the page partial
		$partial->set('details', $this->renderpage($file), false);
	}


	/**
	 * build the packages menu
	 */
	protected function buildmenu($file = null)
	{
		// fetch the menu tree for the packages of this version
		try
		{
			list($tree) = \Cache::get('api.version_'.\Session::get('version').'.packages_menu');
		}
		catch (\CacheNotFoundException $e)
		{
			// get the unique packages
			$model = \DB::select('id', 'package')
				->distinct()
				->from('docblox')
				->where('version_id', '=', \Session::get('version', 0))
				->order_by('package', 'ASC')
				->execute();

			// create the tree index and the tree
			$treeindex = array();
			$tree = array();

			// did we find any packages
			if ($model->count())
			{
				// explode them into a tree, and keep an index so we can find them
				foreach($model as $package)
				{
					$names = explode('/', $package['package']);
					$pointer =& $tree;
					foreach ($names as $name)
					{
						isset($pointer[$name]) or $pointer[$name] = array();
						$pointer =& $pointer[$name];
					}
					$treeindex[$package['package']] =& $pointer;
				}

				// now get all files and add them to the tree
				$model = \DB::select('id', 'package', 'hash', 'file')
					->from('docblox')
					->where('version_id', '=', \Session::get('version', 0))
					->order_by('package', 'ASC')
					->order_by('file', 'ASC')
					->execute();

				// did we find any packages
				if ($model->count())
				{
					//
					foreach($model as $sourcefile)
					{
						$treeindex[$sourcefile['package']][] = $sourcefile;
					}
				}
			}

			// and cache for an hour if not in development, else only for 60 seconds
			\Cache::set('api.version_'.\Session::get('version').'.packages_menu', array($tree), \Fuel::$env == \Fuel::DEVELOPMENT ? 60 : 3600);
		}

		// get the menu state cookie so we can restore state
		$state = str_replace('#apipkg_', '', \Cookie::get('apipkg_menustate', ''));
		$state = empty($state) ? array() : explode(',', $state);

		// closure to generate an unordered list
		$menu = function ($nodes, $depth = 1, $close = false, $id = 0) use(&$menu, $file, $state)
		{
			// open the list
			$output = str_repeat("\t", $depth).'<ul>'."\n";

			// do we have any nodes?
			if ($nodes)
			{
				// loop through the nodes
				foreach ($nodes as $name => $node)
				{
					if (is_numeric($name))
					{
						// this is a file node
						$output .= str_repeat("\t", $depth).'<li id="apipkg_'.$node['id'].($close?'" style="display:none;':'').'"><div'.($file==$node['hash']?' class="current"':'').'><a href="'.$node['hash'].'" title="'.$node['file'].'">'.basename($node['file']).'</a></div></li>'."\n";
					}
					else
					{
						// new menu item
						$nodeid = \Session::get('version').'~'.$id++;

						$output .= str_repeat("\t", $depth).'<li id="apipkg_'.$nodeid.'" class="'.(in_array($nodeid, $state)?'minus"':'plus"').($close?' style="display:none;"':'').'><div>'.$name."</div>\n";

						// and recurse to generate the unordered list for the children
						$output .= $menu($node, $depth+1, ! in_array($nodeid, $state), $id);

						$output .= str_repeat("\t", $depth).'</li>'."\n";
					}
				}
			}

			$output .= str_repeat("\t", $depth).'</ul>'."\n";

			// return the result
			return $output;
		};

		// return it
		return $menu($tree);

	}
}
