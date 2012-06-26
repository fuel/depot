<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.1
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 * @link       http://fuelphp.com
 *
 */

namespace Fuel\Tasks;

/**
 * Docblox XML import Task
 *
 * Run this after docblox has ran, to import the generated xml file
 *
 * Usage:
 * php oil r docblox --version=1.1/develop
 */
class Docblox
{
	// default function if no command is selected. Provided user with menu
	public static function run()
	{
		// check if help was requested
		if (\Cli::option('help', \Cli::option('h', false)))
		{
			echo <<<HELP
Usage:
	php oil refine boxblox --version=1.1/develop

Description:
	Import a Docblox XML structure file into the Fuel Depot database.

Version:
	The version is required, and need to match a github repository name.
HELP;
			return;
		}

		// check if a version was passed
		if ( ! $version = \Cli::option('version', \Cli::option('v', false)))
		{
			\Cli::write('Docblox: a FuelPHP repository version is required.');
			return;
		}
		else
		{
			$version = explode('/', $version);
			if ( ! isset($version[1]))
			{
				\Cli::write('Docblox: FuelPHP repository version must be in the form "major.minor/branch".');
				return;
			}
		}

		// split it in major, minor and branch
		list($version, $branch) = $version;

		$version = explode('.', $version);
		if ( ! isset($version[1]))
		{
			\Cli::write('Docblox: FuelPHP repository version must be in the form "major.minor/branch".');
			return;
		}
		list($major, $minor) = $version;

		// check if we know this version
		$result = \DB::select()->from('versions')->where('major', $major)->where('minor', $minor)->where('branch', $branch)->execute();
		if ( ! $result->count())
		{
			// if a path is given, we can create the version
			if ( $path = \Cli::option('path', \Cli::option('p', false)) and is_dir($path))
			{
				list($insert_id, $rows_affected) = \DB::insert('versions')->set(array(
					'major' => $major,
					'minor' => $minor,
					'branch' => $branch,
					'docbloxpath' => $path,
				))->execute();
				$result = \DB::select()->from('versions')->where('major', $major)->where('minor', $minor)->where('branch', $branch)->execute();
			}
			else
			{
				\Cli::write('Docblox: FuelPHP repository version "'.$major.'.'.$minor.'/'.$branch.'" does not exist.');
			}
		}

		// get the result array
		$result = $result->current();

		// load the API module
		\Module::load('api');

		// initialize the Docblox import class
		\Api\Docblox::set_version($result['major'].'.'.$result['minor'].'/'.$result['branch']);
		\Api\Docblox::set_xmlfile(rtrim($result['docbloxpath'],DS).DS.'structure.xml');

		// process the XML file
		\Api\Docblox::process();
	}
}
