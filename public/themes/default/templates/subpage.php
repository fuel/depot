<?php echo \Theme::instance()->view('partials/page/header'); ?>
<!-- Begin body -->
<body id="top" class="home">

<div id="subheaderWrapper">
	<div id="subheader">

		<div id="logo">
			<h1><a href="/" title="Home"><?php echo \Html::img(\Theme::instance()->asset('img/logo.png'));?></a></h1>
		</div>

		<?php echo \Theme::instance()->view('partials/page/navbar'); ?>

	</div>

</div>

<div id="contentWrapper">

	<div id="content"><?php if (isset($content)) echo $content; ?></div>

</div>

<?php echo \Theme::instance()->view('partials/page/footer'); ?>
