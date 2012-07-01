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

namespace Admin;

/**
 * Administration base controller. All admin controllers should extend this one!
 */
class Controller_Base extends \Controller_Base_Admin
{
	/**
	 * @var	array	pagination settings
	 */
	 protected $pagination = array(
		'pagination_url' => '#',
		'total_items' => 0,
		'per_page' => 10,
		'uri_segment' => 4,
		'template' => array(
			'wrapper_start' => '<div class="pagination pagination-centered"><ul>',
			'wrapper_end' => '</ul></div><div style="clear:both;"></div>',
			'page_start' => '',
			'page_end' => '',
			'regular_start' => '<li>',
			'regular_end' => '</li>',
			'previous_start' => '<li>',
			'previous_end' => '</li>',
			'previous_inactive_start' => '<li class="disabled"><a href="#">',
			'previous_inactive_end' => '</a></li>',
			'previous_mark' => '',
			'next_start' => '<li>',
			'next_end' => '</li>',
			'next_inactive_start' => '<li class="disabled"><a href=""#>',
			'next_inactive_end' => '</a></li>',
			'next_mark' => '',
			'active_start' => '<li class="active"><a href="#">',
			'active_end' => '</a></li>',
		),
	);

	public function before()
	{
		parent::before();

		\Module::load('users');

		// Assign current_user to the instance so controllers can use it
		$this->current_user = \Auth::check() ? \Users\Model_User::find_by_username(\Auth::get_screen_name()) : null;

		// Set a global variable so views can use it
		\View::set_global('current_user', $this->current_user);
	}

}
