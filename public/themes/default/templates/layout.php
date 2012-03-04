<?php
// render the body parts so header assets will be known
isset($navbar) and $navbar instanceOf View and $navbar = $navbar->render();
isset($content) and $content instanceOf View and $content = $content->render();

// output the page header
echo \Theme::instance()->view('partials/page/header');
?>

<!-- Begin body -->
<body id="top" class="home">

<div id="subheaderWrapper">
	<div id="subheader">

		<div id="logo">
			<h1><a href="/" title="Home"><?php echo \Html::img(\Theme::instance()->asset('img/logo.png'));?></a></h1>
		</div>

		<?php echo $navbar; ?>

	</div>

</div>

<div id="contentWrapper">

	<div id="content"><?php if (isset($content)) echo $content; ?></div>

</div>

<?php echo \Theme::instance()->view('partials/page/footer'); ?>
