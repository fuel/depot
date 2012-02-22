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

	<div id="content">

		<div class="page">

			<div class="page-chunk default">

				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<h4 style="text-align:center;">We cannot find the page you are looking for, please click <a href="/">here</a> to go to the homepage.</h4>

			</div>

		</div>

	</div>

</div>

<?php echo \Theme::instance()->view('partials/page/footer'); ?>
