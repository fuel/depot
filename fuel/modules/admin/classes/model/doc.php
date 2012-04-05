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

class Model_Doc extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'page_id',
		'user_id',
		'content',
		'created_at',
	);

	protected static $_belongs_to = array(
		'page',
		'user',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
	);
}
