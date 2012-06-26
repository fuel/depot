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

class Controller_Rest extends \Controller_Rest
{
	/**
	 * before action processing.
	 */
	public function before()
	{
		// this controller only serves REST calls
		if ( ! \Input::is_ajax())
		{
			throw new \HttpNotFoundException();
		}
	}

	public function post_move()
	{
		// do we have move rights?
		if ( \Auth::has_access('access.staff') or \Session::get('ninjauth.authentication.provider', false) == 'github')
		{
			// do we have our input
			if ($current = \Input::post('current', false))
			{
				// get the other fields
				$next = \Input::post('next', 'undefined');
				$previous = \Input::post('previous', 'undefined');
				$parent = \Input::post('parent', 'undefined');

				// get the current id
				$current = str_replace('page_', '', $current);

				// do we have a previous id?
				if ($previous != 'undefined')
				{
					// get the previous id
					$previous = str_replace('page_', '', $previous);

					// we can do a move_to_next_sibling
					$current = Model_Page::find($current);
					$previous = Model_Page::find($previous);

					if ($current and $previous)
					{
						$current->tree_make_next_sibling_of($previous);
					}
				}
				elseif ($next != 'undefined')
				{
					// get the next id
					$next = str_replace('page_', '', $next);

					// we can do a move_to_next_sibling
					$current = Model_Page::find($current);
					$next = Model_Page::find($next);

					if ($current and $next)
					{
						$current->tree_make_previous_sibling_of($next);
					}
				}
				elseif ($parent != 'undefined' and $parent != 'menu_list')
				{
					// get the parent id
					$parent = str_replace('page_', '', $parent);

					// we can do a move_to_first_child
					$current = Model_Page::find($current);
					$parent = Model_Page::find($parent);

					if ($current and $parent)
					{
						$current->tree_make_first_child_of($parent);
					}
				}
				else
				{
					$response = array('response' => 'Moved to unsupported location');
					logger('Error', 'Unknown move request: current:'.$current.', next:'.$next.', previous:'.$previous.', parent:'.$parent, __METHOD__);
				}

				// delete the cached menu to avoid refresh issues
				if ($current)
				{
					\Cache::delete('documentation.version_'.$current->version_id.'.menu');
				}

				$this->response(empty($response) ? array('response' => 'ok') : $response, 200);
			}
			else
			{
				$this->response(array('response' => 'No data passed to the move request!'), 500);
			}
		}
		else
		{
			$this->response(array('response' => 'You are not allowed to move menu items!'), 403);
		}
	}
}
