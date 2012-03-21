<?php

return array(
	/**
	 * The active theme to use.  This can also be set in code using Theme::active('foo');
	 */
	'active' => 'default',

	/**
	 * The fallback theme to use.  If a view is not found in the active theme, this theme
	 * is used as a fallback.  This can also be set in code using Theme::fallback('foo');
	 */
	'fallback' => 'default',

	/**
	 * The theme search paths.  They are searched in the order given.  You can add paths
	 * on the fly via Theme::add_path($path) or Theme::add_paths(array($path1, $path2));
	 */
	'paths' => array(
		APPPATH.'..'.DS.'themes',
	),

	/**
	 * The folder inside the docroot to be used to store theme assets.
	 */
	'assets_folder' => 'themes',

	/**
	 * The extension for theme view files.
	 */
	'view_ext' => '.php',

	/**
	 * Whether to require a theme info file
	 */
	'require_info_file' => false,

	/**
	 * The theme info file name
	 */
	'info_file_name' => 'themeinfo.php',
);
