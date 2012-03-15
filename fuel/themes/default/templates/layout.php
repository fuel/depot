<?php
// output the page header
echo \Theme::instance()->view('global/header');
?>

<!-- Begin body -->
<body id="top" class="home">

<div id="subheaderWrapper">
	<div id="subheader">

		<div id="logo">
			<h1><a href="/" title="Home"><?php echo \Theme::instance()->asset->img('logo.png');?></a></h1>
		</div>

		<?php echo $partials['navbar']; ?>

	</div>

</div>

<div id="contentWrapper">

	<div id="content"><?php echo $partials['content']; ?></div>

</div>

<?php echo \Theme::instance()->view('global/footer'); ?>
