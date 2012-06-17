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

class Controller_Import extends \Controller_Base_Public
{
	/**
	 * Router
	 *
	 * Capture all calls, and use the URI segments to determine the page view
	 *
	 * @access  public
	 * @return  Response
	 */
	public function router($method, array $params)
	{
		// only available in development mode for staff members
		if (\Fuel::$env !== 'development' or ! \Auth::has_access('access.staff'))
		{
			throw new \HttpNotFoundException();
		}

		// fetch and normalize the version and xml file to be imported
		$version = str_replace('\\', '/', $this->param('version', ''));
		$xml = str_replace('\\', DS, $this->param('xml', ''));

		if (empty($version) or empty($xml))
		{
			throw new \Exception('Docblox import: incorrect URI parameters specified.');
		}

		// initialize the Docblox import class
		Docblox::set_version($version);
		Docblox::set_xmlfile($xml.'.xml');

		// capture all debug output
		ob_start();

		// process the XML file
		Docblox::process();

		// fetch all captured output
		$content = ob_get_contents();
		ob_end_clean();

		// output the results
		\Theme::instance()->set_partial('content', 'api/import')->set(array(
			'version' => $version,
			'xmlfile' => $xml.'.xml',
			'content' => $content,
		));
	}
}
