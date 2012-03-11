<?php
/**
 * Closures to generate the HTML for a page docblock
 */

$func_page_docblock = function($docblock)
{
	// title and description
	$result = '<h4>'.$docblock['title'].'</h4><h6>'.html_entity_decode($docblock['description']).'</h6>'."\n";

	if ( ! empty($docblock['tags']))
	{
		foreach ($docblock['tags'] as $tag)
		{
			if (isset($tag['@attributes']))
			{
				$result .= '<dl><dt>'.$tag['@attributes']['name'].'</dt><dd>'.
				(empty($tag['@attributes']['link']) ? $tag['@attributes']['description'] : Html::anchor($tag['@attributes']['link'], $tag['@attributes']['link'])).
				'</dd></dl>'."\n";
			}
		}
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
	if ( ! empty($docblock['title']) or ! empty($docblock['description']))
	{
		$result .= (empty($docblock['title']) ? '' : '<p>'.$docblock['title'].'</p>').
				(empty($docblock['description']) ? '' : '<p>'.html_entity_decode($docblock['description']).'</p>')."\n";
	}

	if ( ! empty($docblock['tags']))
	{
		$result .= '<dl>'."\n";
		foreach ($docblock['tags'] as $tag)
		{
			if (empty($tag['name'])) continue;
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
 * Closure to generate the HTML for the docblock markers
 */
$func_markers = function($markers)
{
	$result = '<h4 class="error">There '.(count($markers) == 1 ? 'is ' : 'are ').count($markers).' documentation error'.(count($markers) == 1 ? '' : 's').' in this file!</h4>'."\n";

	foreach ($markers as $marker)
	{
		$result .= '<div class="spacer '.$marker['type'].'_box">'.$marker['message'].'</div>'."\n";
	}

	return $result;
};

/**
 * Closure to generate the HTML for the constants
 */
$func_constants = function($constants) use($selection)
{
	$result = '';

	foreach ($constants as $constant)
	{
		if ($selection['constant'] == '*' or $selection['constant'] == $constant['name'])
		{
			$result .= '<dl><dt>'.$constant['name'].'</dt><dd>'.$constant['value'].'</dd></dl>'."\n";
		}
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
	if ( ! empty($docblock['title']) or ! empty($docblock['description']))
	{
		$result .= (empty($docblock['title']) ? '' : '<p>'.$docblock['title'].'</p>').
				(empty($docblock['description']) ? '' : '<p>'.html_entity_decode($docblock['description']).'</p>')."\n";
	}

	if ( ! empty($docblock['tags']))
	{
		$result .= '<dl>'."\n";
		foreach ($docblock['tags'] as $tag)
		{
			if (empty($tag['@attributes']['variable'])) continue;
			$result .= '<dt>'.$tag['@attributes']['type'].' : '.$tag['@attributes']['variable'].'</dt><dd>'.
			$tag['@attributes']['description'].
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
	$result = '<div class="properties">'."\n".'<p>Properties</p>'."\n".'<dl>'."\n";

	foreach ($properties as $property)
	{
		$type = isset($property['docblock']['tags'][0]['type']) ? $property['docblock']['tags'][0]['type'] : 'mixed';
		is_array($property['default']) and $property['default'] = implode(' ', $property['default']);

		$result .= '<dt><span>'.$type.'</span> '.$property['name'].'</dt><dd>'."\n".
				(empty($property['final']) ? '' : '<span class="badge red">final</span>').
				(empty($property['static']) ? '' : '<span class="badge green">static</span>').
				(empty($property['public']) ? '' : '<span class="badge lightblue">public</span>').
				(empty($property['protected']) ? '' : '<span class="badge blue">protected</span>').
				(empty($property['private']) ? '' : '<span class="badge darkblue">private</span>').
				'<pre><code>'.$property['default'].'</code></pre></dd>'.
				(empty($property['docblock']['title']) ? '' : '<p>'.$property['docblock']['title'].'</p>').
				"\n";
	}

	$result .= '</dl></div>'."\n";

	return $result;
};

/**
 * Closure to generate the HTML for functions or methods
 */
$func_functions = function($functions, $is_method = false) use($func_variables, $selection)
{
	if ($is_method)
	{
		$result = '<div class="properties">'."\n".'<p>Methods</p>'."\n".'</div>'."\n";
	}
	else
	{
	$result = ''."\n";
	}

	foreach ($functions as $function)
	{
		if ($is_method or $selection['function'] == '*' or $selection['function'] == $function['name'])
		{
			$type = isset($function['docblock']['tags'][0]['type']) ? $function['docblock']['tags'][0]['type'] : 'mixed';

			$result .= '<div class="function">'.
						(empty($function['final']) ? '' : '<span class="badge red">final</span>').
						(empty($function['public']) ? '' : '<span class="badge lightblue">public</span>').
						(empty($function['protected']) ? '' : '<span class="badge blue">protected</span>').
						(empty($function['private']) ? '' : '<span class="badge darkblue">private</span>').
						(empty($function['static']) ? '' : '<span class="badge green">static</span>').
						'<span>'.$type.'</span> '.$function['name'].
						'(';

			$argcount = 0;
			foreach ($function['arguments'] as $argument)
			{
				if ($argcount++ > 0) $result .= ', ';
				$result .= $argument['name'];
				empty($argument['default']) or $result .= ' = '.$argument['default'];
			}

			$result .= ')</div>'."\n";

			if ( ! empty($function['docblock']))
			{
				$result .= $func_variables($function['docblock']);
			}

			$result .= '<div class="clearfix"></div>'."\n";
		}
	}

	return $result;
};

/**
 * Closure to generate the HTML for classes
 */
$func_classes = function($classes) use($func_docblock, $func_properties, $func_functions, $selection)
{
	$result = ''."\n";

	foreach ($classes as $class)
	{
		if ($selection['class'] == '*' or $selection['class'] == $class['name'])
		{
			$result .= '<div class="class">'."\n".'<dt>';

			if ( ! empty($class['namespace']))
			{
				$result .= '<span class="badge">'.$class['namespace'].'</span>';
			}

			$result .= ' '.$class['name'];

			if ( ! empty($class['extends']))
			{
				$result .= ' extends ';
			}

			$extcount = 0;
			foreach ($class['extends'] as $extends)
			{
				if ($extcount++ > 0) $result .= ', ';
				$result .= $extends;
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
?>
<!-- API Documentation -->
<div>

	<!-- API file header -->
	<div id="api_info">
		<h2><?php echo $record['file']; ?></h2>
	</div>

	<!-- API documentation error block -->
	<?php if( \Auth::check() and ! empty($record['markers'])): ?>
		<div id="api_errors" class="no-box">
			<?php echo $func_markers($record['markers']); ?>
		</div>
		<div class="clearfix"></div>
	<?php endif; ?>

	<!-- API page docblock -->
	<?php if( ! empty($record['docblock'])): ?>
		<div id="api_docblock">
			<?php echo $func_page_docblock($record['docblock']); ?>
		</div>
		<div class="clearfix"></div>
	<?php endif; ?>

	<!-- API global constants block -->
	<?php if( ! empty($record['constants']) and ! empty($selection['constant'])): ?>
		<div id="api_constants">
			<h3>Constants</h3>
			<?php echo $func_constants($record['constants']); ?>
		</div>
		<div class="clearfix"></div>
	<?php endif; ?>

	<!-- API functions block -->
	<?php if( ! empty($record['functions']) and ! empty($selection['function'])): ?>
		<div id="api_functions">
			<h3>Functions</h3>
			<?php echo $func_functions($record['functions']); ?>
		</div>
		<div class="clearfix"></div>
	<?php endif; ?>

	<!-- API classes block -->
	<?php if( ! empty($record['classes']) and ! empty($selection['class'])): ?>
		<div id="api_classes">
			<h3>Classes</h3>
			<?php echo $func_classes($record['classes']); ?>
		</div>
		<div class="clearfix"></div>
	<?php endif; ?>

</div>
