<?php
/**
 * Closures to generate the HTML for a page docblock
 */

$func_page_docblock = function($docblock) {

	// title and description
	$result = '<h4>'.$docblock['title'].'</h4><h6>'.$docblock['description'].'</h6>'."\n";

	if ( ! empty($docblock['tags']))
	{
		foreach ($docblock['tags'] as $tag)
		{
			$result .= '<dl><dt>'.$tag['name'].'</dt><dd>'.
			(empty($tag['link']) ? $tag['description'] : Html::anchor($tag['link'], $tag['link'])).
			'</dd></dl>'."\n";
		}
	}

	return $result;
};

/**
 * Closures to generate the HTML for a docblock
 */

$func_docblock = function($docblock) {

	// title and description
	$result = '<div class="docblock">'."\n".
			(empty($docblock['title']) ? '' : '<p>'.$docblock['title'].'</p>').
			(empty($docblock['description']) ? '' : '<p>'.$docblock['description'].'</p>')."\n";

	if ( ! empty($docblock['tags']))
	{
		$result .= '<dl>';
		foreach ($docblock['tags'] as $tag)
		{
			$result .= '<dt>'.$tag['name'].'</dt><dd>'.
			(empty($tag['link']) ? $tag['description'] : Html::anchor($tag['link'], $tag['link'])).
			'</dd></dl>'."\n";
		}
		$result .= '</dl>'."\n".'<div class="clearfix"></div>'."\n";
	}

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
 * Closure to generate the HTML for the constants and properties
 */
$func_variables = function($variables)
{
	$result = '';

	foreach ($variables as $variable)
	{
		$result .= '<dl><dt>'.$variable['name'].'</dt><dd>'.$variable['value'].'</dd></dl>'."\n";
	}

	return $result;
};

/**
 * Closure to generate the HTML for functions or methods
 */
$func_functions = function($functions) use($func_docblock)
{
	$result = ''."\n";

	foreach ($functions as $function)
	{
		$result .= '<div id="function"><dt>'.
					$function['name'].
					'(';

		$argcount = 0;
		foreach ($function['arguments'] as $argument)
		{
			if ($argcount++ > 0) $result .= ', ';
			$result .= $argument['name'];
			empty($argument['default']) or $result .= ' = ' . $argument['default'];
		}

		$result .= ')</dt></div>'."\n".'<div class="clearfix"></div>'."\n";

		if ( ! empty($function['docblock']))
		{
			$result .= $func_docblock($function['docblock']);
		}

	}

	return $result;
};
?>
<!-- API Documentation -->
<div id="api">

	<!-- API file header -->
	<div id="api_info">
		<h2><?php echo $record['file']; ?></h2>
	</div>

	<!-- API documentation error block -->
	<?php if( ! empty($record['markers'])): ?>
		<div id="api_errors" class="no-box">
			<?php echo $func_markers($record['markers']); ?>
		</div>
		<div class="clearfix"></div>
	<?php endif; ?>

	<!-- API page docblock -->
	<?php if( ! empty($record['docblock'])): ?>
		<div id="api_docblock">
			<?php echo $func_page_docblock($record['docblock']); ?>
			<div class="clearfix"></div>
		</div>
	<?php endif; ?>

	<!-- API global constants block -->
	<?php if( ! empty($record['constants'])): ?>
		<div id="api_constants">
			<h3>Constants</h3>
			<?php echo $func_variables($record['constants']); ?>
			<div class="clearfix"></div>
		</div>
	<?php endif; ?>

	<!-- API functions block -->
	<?php if( ! empty($record['functions'])): ?>
		<div id="api_functions">
			<h3>Functions</h3>
			<?php echo $func_functions($record['functions']); ?>
			<div class="clearfix"></div>
		</div>
	<?php endif; ?>

	<!-- API classes block -->
	<?php if( ! empty($record['classes'])): ?>
		<div id="api_classes">
			<h3>Classes</h3>
<?php Debug::dump($class); ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

</div>
