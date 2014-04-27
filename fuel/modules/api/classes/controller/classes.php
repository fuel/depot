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

class Controller_Classes extends Controller_Apibase
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
			$model = Model_Class::query()
				->related('docblox')
				->where('docblox.version_id', '=', \Session::get('version', 0))
				->order_by('namespace', 'ASC')
				->rows_limit(1)
				->get();

			// and did we?
			$model and \Messages::redirect('api/classes/'.reset($model)->docblox->hash);
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
		// fetch the menu tree for the files of this version
		try
		{
			list($tree) = \Cache::get('api.version_'.\Session::get('version').'.classes_menu');
		}
		catch (\CacheNotFoundException $e)
		{
			// get all classes
			$model = Model_Class::query()
				->related('docblox')
				->where('docblox.version_id', '=', \Session::get('version', 0))
				->order_by('namespace', 'ASC')
				->order_by('name', 'ASC')
				->get();

			// create the tree index and the tree
			$treeindex = array();
			$tree = array();

			// did we find any packages
			if ($model)
			{
				// explode them into a tree, and keep an index so we can find them
				foreach($model as $class)
				{
					$names = explode('\\', $class->namespace);
					$pointer =& $tree;
					foreach ($names as $name)
					{
						isset($pointer[$name]) or $pointer[$name] = array();
						$pointer =& $pointer[$name];
					}
					$treeindex[$class->namespace] =& $pointer;
				}

				foreach($model as $class)
				{
					$treeindex[$class->namespace][] = array(
						'id' => $class->docblox->id,
						'hash' => $class->docblox->hash,
						'file' => $class->docblox->file,
						'class' => $class->name,
					);
				}
			}

			// and cache for an hour if not in development, else only for 60 seconds
			\Cache::set('api.version_'.\Session::get('version').'.classes_menu', array($tree), \Fuel::$env == \Fuel::DEVELOPMENT ? 60 : 3600);
		}

		// get the menu state cookie so we can restore state
		$state = str_replace('#apins_', '', \Cookie::get('apins_menustate', ''));
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
						$output .= str_repeat("\t", $depth).'<li id="apins_'.$node['id'].($close?'" style="display:none;':'').'"><div'.($file==$node['hash']?' class="current"':'').'><a href="'.$node['hash'].'" title="'.$node['file'].'">'.$node['class'].'</a></div></li>'."\n";
					}
					else
					{
						// new menu item
						$nodeid = \Session::get('version').'~'.$id++;

						$output .= str_repeat("\t", $depth).'<li id="apins_'.$nodeid.'" class="'.(in_array($nodeid, $state)?'minus"':'plus"').($close?' style="display:none;"':'').'><div>'.$name."</div>\n";

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
