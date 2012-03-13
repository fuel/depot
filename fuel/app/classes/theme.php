<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.1
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * Handles loading theme views and assets.
 */
class Theme
{
	/**
	 * All the Theme instances
	 *
	 * @var  array
	 */
	protected static $instances = array();

	/**
	 * Acts as a Multiton.  Will return the requested instance, or will create
	 * a new named one if it does not exist.
	 *
	 * @param   string    $name  The instance name
	 *
	 * @return  Theme
	 */
	public static function instance($name = '_default_', array $config = array())
	{
		if ( ! \array_key_exists($name, static::$instances))
		{
			static::$instances[$name] = static::forge($config);
		}

		return static::$instances[$name];
	}

	/**
	 * Gets a new instance of the Theme class.
	 *
	 * @param   array  $config  Optional config override
	 * @return  Theme
	 */
	public static function forge(array $config = array())
	{
		return new static($config);
	}

	/**
	 * @var  array  $paths  Possible locations for themes
	 */
	protected $paths = array();

	/**
	 * @var  array  $active  Currently active theme
	 */
	protected $active = array(
		'name' => null,
		'path' => null,
		'template' => null,
		'asset_base' => null,
		'info' => array(),
	);

	/**
	 * @var  array  $fallback  Fallback theme
	 */
	protected $fallback = array(
		'name' => null,
		'path' => null,
		'asset_base' => null,
		'info' => array(),
	);

	/**
	 * @var  array  $config  Theme config
	 */
	protected $config = array(
		'active' => 'default',
		'fallback' => 'default',
		'paths' => array(),
		'assets_folder' => 'themes',
		'view_ext' => '.html',
		'require_info_file' => false,
		'info_file_name' => 'theme.info',
		'info_file_type' => 'php',
	);

	/**
	 * @var  array  $partials	Storage for defined template partials
	 */
	protected $partials = array();

	/**
	 * Sets up the theme object.  If a config is given, it will not use the config
	 * file.
	 *
	 * @param   array  $config  Optional config override
	 * @return  void
	 */
	public function __construct(array $config = array())
	{
		if (empty($config))
		{
			\Config::load('theme', true, false, true);
			$config = \Config::get('theme', false);
		}

		// Order of this addition is important, do not change this.
		$this->config = $config + $this->config;

		// define the default theme paths, the active and the fallback theme
		$this->add_paths($this->config['paths']);
		$this->active($this->config['active']);
		$this->fallback($this->config['fallback']);
	}

	/**
	 * Sets the currently active theme.  Will return the currently active
	 * theme.  It will throw a \ThemeException if it cannot locate the theme.
	 *
	 * @param   string  $theme  Theme name to set active
	 * @return  array   The theme array
	 * @throws  \ThemeException
	 */
	public function active($theme = null)
	{
		if ($theme !== null)
		{
			$this->active = $this->create_theme_array($theme);
		}

		return $this->active;
	}

	/**
	 * Sets the fallback theme.  This theme will be used if a view or asset
	 * cannot be found in the active theme.  Will return the fallback
	 * theme.  It will throw a \ThemeException if it cannot locate the theme.
	 *
	 * @param   string  $theme  Theme name to set active
	 * @return  array   The theme array
	 * @throws  \ThemeException
	 */
	public function fallback($theme = null)
	{
		if ($theme !== null)
		{
			$this->fallback = $this->create_theme_array($theme);
		}

		return $this->fallback;
	}

	/**
	 * Loads a view from the currently active theme, the fallback theme, or
	 * via the standard FuelPHP cascading file system for views
	 *
	 * @param   string  $view         View name
	 * @param   array   $data         View data
	 * @param   bool    $auto_filter  Auto filter the view data
	 * @return  View    New View object
	 */
	public function view($view, $data = array(), $auto_filter = null)
	{
		if ($this->active['path'] === null)
		{
			throw new \ThemeException('You must set an active theme.');
		}

		return \View::forge($this->find_file($view), $data, $auto_filter);
	}

	/**
	 * Loads an asset from the currently loaded theme.
	 *
	 * @param   string  $path  Relative path to the asset
	 * @return  string  Full asset URL or path if outside docroot
	 */
	public function asset($path = null)
	{
		if ($this->active['path'] === null)
		{
			throw new \ThemeException('You must set an active theme.');
		}

		if (func_num_args())
		{
			// a path is given, stick it on the base and return it
			if ($this->active['asset_base'])
			{
				return $this->active['asset_base'].$path;
			}

			return $this->active['path'].$path;
		}
		else
		{
			// no path is given, return the asset instance for chaining
			return \Asset::instance('theme_'.$this->active['name']);
		}
	}

	/**
	 * Sets a template for a theme
	 *
	 * @param   string  $template Name of the template view
	 * @return  View
	 */
	public function set_template($template)
	{
		// make sure the template is a View
		if (is_string($template))
		{
			$this->active['template'] = $this->view($template);
		}
		else
		{
			$this->active['template'] = $template;
		}

		// return the template view for chaining
		return $this->active['template'];
	}

	/**
	 * Get a partial so it can be manipulated
	 *
	 * @return  View
	 * @throws  \ThemeException
	 */
	public function get_template()
	{
		// make sure the partial entry exists
		if (empty($this->active['template']))
		{
			throw new \ThemeException('No valid template could be found. Use set_template() to define a page template.');
		}

		// return the template view for chaining
		return $this->active['template'];
	}

	/**
	 * Sets a partial for the current page
	 *
	 * @param   string  				$section   Name of the partial section in the template
	 * @param   string|View|ViewModel	$view      View, or name of the view
	 * @param   bool					$overwrite If true overwrite any already defined partials for this section
	 * @return  View
	 */
	public function set_partial($section, $view, $overwrite = false)
	{
		// make sure the partial entry exists
		array_key_exists($section, $this->partials) or $this->partials[$section] = array();

		// make sure the partial is a view
		if (is_string($view))
		{
			$name = $view;
			$view = $this->view($view);
		}
		else
		{
			$name = count($this->partials[$section]);
		}

		// store the partial
		if ($overwrite)
		{
			$this->partials[$section] = array($name => $view);
		}
		else
		{
			$this->partials[$section][$name] = $view;
		}

		// return the partial view object for chaining
		return $this->partials[$section][$name];
	}

	/**
	 * Get a partial so it can be manipulated
	 *
	 * @param   string	$section   Name of the partial section in the template
	 * @param   string	$view      name of the view
	 * @return  View
	 * @throws  \ThemeException
	 */
	public function get_partial($section, $view)
	{
		// make sure the partial entry exists
		if (array_key_exists($section, $this->partials) and array_key_exists($view, $this->partials[$section]))
		{
			return $this->partials[$section][$view];
		}
		else
		{
			throw new \ThemeException(sprintf('No partial named "%s" can be found in the "%s" section.', $view, $section));
		}
	}

	/**
	 * Render the partials and the theme template
	 *
	 * @param   bool	$render_partials If false do not pre-render the partials
	 * @param   bool	$render_template If false do not render the template but return the view object
	 * @return  string|View
	 * @throws  \ThemeException
	 */
	public function render()
	{
		// pre-process all defined partials
		foreach ($this->partials as $key => $partials)
		{
			$output = '';
			foreach ($partials as $index => $partial)
			{
				// render the partial
				$output .= $partial->render();
			}

			// store the rendered output
			$this->partials[$key] = $output;
		}

		// do we have a template view?
		if (empty($this->active['template']))
		{
			throw new \ThemeException('No valid template could be found. Use set_template() to define a page template.');
		}

		// assign the partials to the template
		$this->active['template']->set('partials', $this->partials, false);

		// return the template
		return $this->active['template'];
	}

	/**
	 * Adds the given path to the theme search path.
	 *
	 * @param   string  $path  Path to add
	 * @return  void
	 */
	public function add_path($path)
	{
		$this->paths[] = rtrim($path, DS).DS;
	}

	/**
	 * Adds the given paths to the theme search path.
	 *
	 * @param   array  $paths  Paths to add
	 * @return  void
	 */
	public function add_paths(array $paths)
	{
		array_walk($paths, array($this, 'add_path'));
	}

	/**
	 * Gets an option for the active theme.
	 *
	 * @param   string  $option   Option to get
	 * @param   mixed   $default  Default value
	 * @return  mixed
	 */
	public function option($option, $default = null)
	{
		if ( ! isset($this->active['info']['options'][$option]))
		{
			return $default;
		}

		return $this->active['info']['options'][$option];
	}

	/**
	 * Sets an option for the active theme.
	 *
	 * NOTE: This does NOT update the theme.info file.
	 *
	 * @param   string  $option   Option to get
	 * @param   mixed   $value    Value
	 * @return  $this
	 */
	public function set_option($option, $value)
	{
		$this->active['info']['options'][$option] = $value;

		return $this;
	}

	/**
	 * Finds the given theme by searching through all of the theme paths.  If
	 * found it will return the path, else it will return `false`.
	 *
	 * @param   string  $theme  Theme to find
	 * @return  string|false  Path or false if not found
	 */
	public function find($theme)
	{
		foreach ($this->paths as $path)
		{
			if (is_dir($path.$theme))
			{
				return $path.$theme.DS;
			}
		}

		return false;
	}

	/**
	 * Gets an array of all themes in all theme paths, sorted alphabetically.
	 *
	 * @return  array
	 */
	public function all()
	{
		$themes = array();
		foreach ($this->paths as $path)
		{
			foreach(glob($path.'*', GLOB_ONLYDIR) as $theme)
			{
				$themes[] = basename($theme);
			}
		}
		sort($themes);

		return $themes;
	}

	/**
	 * Get a value from the info array
	 *
	 * @return  mixed
	 */
	public function info($var = null, $default = null, $theme = null)
	{
		// if no theme is given
		if ($theme === null)
		{
			// if no var to search is given return the entire info array
			if ($var === null)
			{
				return $this->active['info'];
			}

			// find the value in the active theme info
			if (($value = \Arr::get($this->active['info'], $var, null)) !== null)
			{
				return $value;
			}

			// and if not found, check the fallback
			elseif (($value = \Arr::get($this->fallback['info'], $var, null)) !== null)
			{
				return $value;
			}
		}

		// or if we have a specific theme
		else
		{
			// fetch the info from that theme
			$info = $this->all_info($theme);

			// and return the requested value
			return $var === null ? $info : \Arr::get($info, $var, $default);
		}

		// not found, return the given default value
		return $default;
	}

	/**
	 * Reads in the theme.info file for the given (or active) theme.
	 *
	 * @param   string  $theme  Name of the theme (null for active)
	 * @return  array   Theme info array
	 */
	public function all_info($theme = null)
	{
		if ($theme === null)
		{
			$theme = $this->active;
		}

		if (is_array($theme))
		{
			$path = $theme['path'];
			$name = $theme['name'];
		}
		else
		{
			$path = $this->find($theme);
			$name = $theme;
			$theme = array(
				'name' => $name,
				'path' => $path
			);
		}

		if ( ! $path)
		{
			throw new \ThemeException(sprintf('Could not find theme "%s".', $theme));
		}

		if (($file = $this->find_file($this->config['info_file_name'], array($theme))) == $this->config['info_file_name'])
		{
			if ($this->config['require_info_file'])
			{
				throw new \ThemeException(sprintf('Theme "%s" is missing "%s".', $name, $this->config['info_file_name']));
			}
			else
			{
				return array();
			}
		}

		$type = strtolower($this->config['info_file_type']);
		switch ($type)
		{
			case 'ini':
				$info = parse_ini_file($file, true);
			break;

			case 'json':
				$info = json_decode(file_get_contents($file), true);
			break;

			case 'yaml':
				$info = \Format::forge(file_get_contents($file), 'yaml')->to_array();
			break;

			case 'php':
				$info = include($file);
			break;

			default:
				throw new \ThemeException(sprintf('Invalid info file type "%s".', $type));
		}

		return $info;
	}

	/**
	 * Find the absolute path to a file in a set of Themes.  You can optionally
	 * send an array of themes to search.  If you do not, it will search active
	 * then fallback (in that order).
	 *
	 * @param   string  $view    name of the view to find
	 * @param   array   $themes  optional array of themes to search
	 * @return  string  absolute path to the view
	 * @throws  \ThemeException  when not found
	 */
	protected function find_file($view, $themes = null)
	{
		if ($themes === null)
		{
			$themes = array($this->active, $this->fallback);
		}

		foreach ($themes as $theme)
		{
			$ext   = pathinfo($view, PATHINFO_EXTENSION) ?
				'.'.pathinfo($view, PATHINFO_EXTENSION) : $this->config['view_ext'];
			$file  = (pathinfo($view, PATHINFO_DIRNAME) ?
					str_replace(array('/', DS), DS, pathinfo($view, PATHINFO_DIRNAME)).DS : '').
				pathinfo($view, PATHINFO_FILENAME);
			if (empty($theme['find_file']))
			{
				if (is_file($path = $theme['path'].$file.$ext))
				{
					return $path;
				}
			}
			else
			{
				if ($path = \Finder::search($theme['path'], $file, $ext))
				{
					return $path;
				}
			}
		}

		// not found, return the viewname to fall back to the standard View processing
		return $view;
	}

	/**
	 * Creates a theme array by locating the given theme and setting all of the
	 * option.  It will throw a \ThemeException if it cannot locate the theme.
	 *
	 * @param   string  $theme  Theme name to set active
	 * @return  array   The theme array
	 * @throws  \ThemeException
	 */
	protected function create_theme_array($theme)
	{
		if ( ! is_array($theme))
		{
			if ( ! $path = $this->find($theme))
			{
				throw new \ThemeException(sprintf('Theme "%s" could not be found.', $theme));
			}

			$theme = array(
				'name' => $theme,
				'path' => $path,
				'asset_base' => null,
			);
		}
		else
		{
			if ( ! isset($theme['name']) or ! isset($theme['path']))
			{
				throw new \ThemeException('Theme name and path must be given in array config.');
			}
		}

		if ( ! isset($theme['info']))
		{
			$theme['info'] = $this->all_info($theme);
		}

		if ( ! isset($theme['asset_base']))
		{
			$assets_folder = rtrim($this->config['assets_folder'], DS).DS;
			$theme['asset_base'] = $assets_folder.$theme['name'].DS;
			if (is_dir(DOCROOT.$assets_folder))
			{
				$theme['asset_base'] = DOCROOT.$theme['asset_base'];
			}
			else
			{
			}
		}

		// asset_base always uses forward slashes (DS is a backslash on Windows)
		$theme['asset_base'] = str_replace(DS, '/', $theme['asset_base']);
		// create an asset instance for our theme
		if (\Asset::instance('theme_'.$theme['name']) === false)
		{
			\Asset::forge('theme_'.$theme['name'], array('paths' => array($theme['asset_base'])));
		}

		return $theme;
	}
}
