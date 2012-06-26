<?php
/**
 * Closure to generate the HTML for the docblock markers
 */
$func_markers = function($markers)
{
	// some storage for the result
	$result = '';

	// for logged-in users only
	if(\Auth::check())
	{
		// if there are markers, display them
		if($markers)
		{
			$result .= '<div id="docs_errors" class="no-box">'."\n";
			$result .= '<h4 class="error">There '.(count($markers) == 1 ? 'is ' : 'are ').count($markers).' documentation error'.(count($markers) == 1 ? '' : 's').' in this file!</h4>'."\n";

			foreach ($markers as $marker)
			{
				$result .= '<div class="spacer '.$marker['type'].'_box">'.$marker['message'].'</div>'."\n";
			}

			$result .= '</div>'."\n".'<div class="clearfix"></div>'."\n";
		}
	}

	return $result;
};

/**
 * Closures to generate the HTML for a page docblock
 */

$func_page_docblock = function($docblock)
{
	// some storage for the result
	$result = '';

	// if there is docblock data, display it
	if($docblock)
	{
		$result .= '<div id="docs_docblock">'."\n";

		// title and description
		$result .= '<h5>'.$docblock['description'].'</h5><h6>'.html_entity_decode(implode('<br />', $docblock['long-description'])).'</h6>'."\n";

		// since info
		empty($docblock['since']) or $result .= '<h6>Since: '.$docblock['since'].'</h6>'."\n";

		if ( ! empty($docblock['tags']))
		{
			foreach ($docblock['tags'] as $tag)
			{
				$result .= '<dl><dt>'.$tag['name'].'</dt><dd>'.
				(empty($tag['link']) ? $tag['description'] : Html::anchor($tag['link'], $tag['link'])).
				'</dd></dl>'."\n";
			}
		}

		$result .= '</div>'."\n".'<div class="clearfix"></div>'."\n";
	}

	return $result;
};

/**
 * Closure to generate the HTML for the constants
 */
$func_variables = function($docblock)
{
	$result = '<div class="docblock">'."\n";

	// title and description
	empty($docblock['description']) or $result .= '<p>'.$docblock['description'].'</p>';

	// title and description
	empty($docblock['long-description']) or $result .= '<p>'.html_entity_decode(implode('<br />', $docblock['long-description'])).'</p>';

	if ( ! empty($docblock['tags']))
	{
		$result .= '<dl>'."\n";
		foreach ($docblock['tags'] as $tag)
		{
			empty($tag['variable']) or $result .= '<dt>'.$tag['type'].' : '.$tag['variable'].'</dt><dd>'.$tag['description'].'</dd>'."\n";
		}
		$result .= '</dl>'."\n";
	}

	$result .= '</div>'."\n";

	return $result;
};

/**
 * Closure to generate the HTML for the constants
 */
$func_constants = function($constants) use($func_variables)
{
	// some storage for the result
	$result = '';

	// if there is constants data, display it
	if($constants)
	{
		$result .= '<div id="docs_constants">'."\n".'<h4>Constants</h4>'."\n";

		foreach ($constants as $constant)
		{
			$result .= '<dl><dt>'.$constant->name.'</dt><dd>'.$constant->value.'</dd></dl>'."\n";
		}

		empty($function->docblock) or  $result .= $func_variables($function->docblock);

		$result .= '</div>'."\n".'<div class="clearfix"></div>'."\n";
	}

	return $result;
};

/**
 * Closure to generate the HTML for functions or methods
 */
$func_functions = function($functions, $is_method = false) use($func_variables)
{
	// some storage for the result
	$result = '';

	// if there is function/method data, display it
	if($functions)
	{
		// block header
		if ($is_method)
		{
			$result = '<div class="properties">'."\n".'<p>Methods</p>'."\n".'</div>'."\n";
		}
		else
		{
			$result .= '<div id="docs_functions">'."\n".'<h4>Functions</h4>'."\n";
		}

		foreach ($functions as $function)
		{
			// function/method header
			$result .= '<div class="function">'.
						(empty($function['final']) ? '' : '<span class="badge red">final</span>').
						($function['visibility'] == 'public' ? '<span class="badge lightblue">public</span>' : '').
						($function['visibility'] == 'protected' ? '<span class="badge blue">protected</span>' : '').
						($function['visibility'] == 'private' ? '<span class="badge darkblue">private</span>' : '').
						(empty($function['protected']) ? '' : '<span class="badge blue">protected</span>').
						(empty($function['private']) ? '' : '<span class="badge darkblue">private</span>').
						(empty($function['static']) ? '' : '<span class="badge green">static</span>').
						'<span>'.$function->type.'</span> '.$function->name.
						'(';

			$argcount = 0;
			foreach ($function->arguments as $argument)
			{
				if ($argcount++ > 0) $result .= ', ';
				$result .= $argument['name'];
				empty($argument['default']) or $result .= ' = '.$argument['default'];
			}

			$result .= ')</div>'."\n";

			empty($function->docblock) or $result .= $func_variables($function->docblock);

			$result .= '<div class="clearfix"></div>'."\n";
		}

		$result .= '</div>'."\n".'<div class="clearfix"></div>'."\n";
	}

	return $result;
};

/**
 * Closures to generate the HTML for a page, class or method docblock
 */

$func_docblock = function($docblock)
{
	$result = '<div class="docblock">'."\n";

	// title and description
	empty($docblock['description']) or $result .= '<p>'.$docblock['description'].'</p>';

	// title and description
	empty($docblock['long-description']) or $result .= '<p>'.html_entity_decode(implode('<br />', $docblock['long-description'])).'</p>';

	if ( ! empty($docblock['tags']))
	{
		$result .= '<dl>'."\n";
		foreach ($docblock['tags'] as $tag)
		{
			$result .= '<dt>'.$tag['name'].'</dt><dd>'.
			(empty($tag['link']) ? $tag['description'] : Html::anchor($tag['link'], $tag['link'])).
			'</dd>'."\n";
		}
		$result .= '</dl>'."\n";
	}

	$result .= '</div>'."\n";

	return $result;
};

/**
 * Closure to generate the HTML for the properties
 */
$func_properties = function($properties)
{
	// some storage for the result
	$result = '';

	// if there is function/method data, display it
	if($properties)
	{
		$result = '<div class="properties">'."\n".'<p>Properties</p>'."\n".'<dl>'."\n";

		foreach ($properties as $property)
		{
			is_array($property['value']) and $property['value'] = implode(' ', $property['value']);

			$result .= '<dt><span>'.$property['type'].'</span> '.$property['name'].'</dt><dd>'."\n".
					($property['final'] == 'false' ? '' : '<span class="badge red">final</span>').
					($property['visibility'] == 'public' ? '<span class="badge lightblue">public</span>' : '').
					($property['visibility'] == 'protected' ? '<span class="badge blue">protected</span>' : '').
					($property['visibility'] == 'private' ? '<span class="badge darkblue">private</span>' : '').
					($property['static'] == 'false' ? '' : '<span class="badge green">static</span>').
					(empty($property['value']) ? '' : '<pre><code>'.$property['value'].'</code></pre></dd>').
					"\n";
		}

		$result .= '</dl></div>'."\n";
	}

	return $result;
};

/**
 * Closure to generate the HTML for classes
 */
$func_classes = function($classes) use($func_functions, $func_docblock, $func_properties)
{
	// some storage for the result
	$result = '';

	// if there is class data, display it
	if($classes)
	{
		// block header
		$result .= '<div id="docs_classes">'."\n".'<h4>Classes</h4>'."\n";

		foreach ($classes as $class)
		{
			$result .= '<div class="class">'."\n".'<dt>';
			empty($class['namespace']) or $result .= '<span class="badge">'.$class->namespace.'</span>';
			$result .= ' '.$class['name'];
			if ( ! empty($class['extends']))
			{
				$result .= ' extends ';
				$extcount = 0;
				foreach (array($class['extends']) as $extends)
				{
					if ($extcount++ > 0) $result .= ', ';
					$result .= $extends;
				}
			}

			$result .= '</dt></div>'."\n";
			$result .= '<div class="clearfix"></div>'."\n";

			// add the class docblock
			empty($class['docblock']) or $result .= $func_docblock($class['docblock'])."\n".'<div class="clearfix"></div>'."\n";

			// add the class properties
			empty($class['properties']) or $result .= $func_properties($class['properties'])."\n".'<div class="clearfix"></div>'."\n";

			// add the class methods
			empty($class['methods']) or $result .= $func_functions($class['methods'], true)."\n".'<div class="clearfix"></div>'."\n";
		}
	}

	$result .= '<div class="clearfix"></div>'."\n";

	return $result;
};


/**
 *******************************************************************************
 */

?>
<!-- API Documentation -->
<div>

	<!-- API file header -->
	<div id="docs_info">
		<h3><?php echo $model->file; ?></h3>
	</div>

	<!-- API documentation error block -->
	<?php echo empty($model->markers) ? '' : $func_markers($model->markers); ?>

	<!-- API page docblock -->
	<?php echo empty($model->docblock) ? '' : $func_page_docblock($model->docblock); ?>

	<!-- API global constants block -->
	<?php echo empty($model->constant) ? '' : $func_constants($model->constant); ?>

	<!-- API functions block -->
	<?php echo empty($model->function) ? '' : $func_functions($model->function, false); ?>

	<!-- API classes block -->
	<?php echo empty($model->class) ? '' : $func_classes($model->class); ?>
</div>
