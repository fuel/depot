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

class Controller_Move extends \Controller_Base_Public
{
	/**
	 * Documentation version index page, determine which default page to load
	 */
	public function action_index($from = null, $action = null, $to = null)
	{
		// admin's only!
		if ( ! \Auth::has_access('access.staff'))
		{
			throw new \HttpNotFoundException();
		}

		// validate the parameters
		if ( ! is_numeric($from) or ! $from = Model_Page::find($from))
		{
			\Messages::error('Invalid "from" page number');
			\Messages::redirect('documentation');
		}

		if ( ! is_numeric($to) or ! $to = Model_Page::find($to))
		{
			\Messages::error('Invalid "to" page number');
			\Messages::redirect('documentation');
		}

		if ( ! in_array($action, array('firstchild', 'lastchild', 'nextsibling', 'previoussibling')) )
		{
			\Messages::error('Invalid action specified');
			\Messages::redirect('documentation');
		}

		// make sure the two nodes are part of the same tree
		if ( ! $from->tree_same_tree_as($to, 'Move::action_index'))
		{
			\Messages::error('"from" and "to" don\'t belong to the same menu');
			\Messages::redirect('documentation');
		}

		// ok, everything checks out, let's go
		switch ($action)
		{
			case 'firstchild':
				$from->tree_make_first_child_of($to);
				break;

			case 'lastchild':
				$from->tree_make_last_child_of($to);
				break;

			case 'nextsibling':
				$from->tree_make_next_sibling_of($to);
				break;

			case 'previoussibling':
				$from->tree_make_previous_sibling_of($to);
				break;

		}


		\Messages::success('page moved succesfully');
		\Messages::redirect('documentation/page/'.$to->id);
	}

}
