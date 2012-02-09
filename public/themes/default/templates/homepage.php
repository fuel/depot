<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<!-- Begin head -->
<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="fuelphp.com" />
<meta name="copyright" content="fuelphp.com" />
<meta name="robots" content="index, follow" />
<meta name="distribution" content="global" />
<meta name="resource-type" content="document" />
<meta name="language" content="en" />

<link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />

<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic,bold,bolditalic" type="text/css" />
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold" type="text/css" />

<link rel="stylesheet" href="<?php echo \Theme::instance()->asset('css/reset.css'); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo \Theme::instance()->asset('css/typo.css'); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo \Theme::instance()->asset('css/global.css'); ?>" type="text/css" />

<title>Fuel Depot&nbsp;&rsaquo;&nbsp;The documentation repository for the FuelPHP framework.</title>

</head>

<!-- Begin body -->
<body id="top" class="home">

<div id="headerWrapper">
	<div id="header">

		<div id="logo">
			<h1><a href="/" title="Home"><?php echo \Html::img(\Theme::instance()->asset('img/logo.png'));?></a></h1>
		</div>

		<ul id="nav">
			<li><a href="/about">About</a></li>
			<li><a href="/docs">Docs</a></li>
			<li><a href="/devdocs">Dev-Docs</a></li>
			<li><a href="/api">Class API</a></li>
			<li><a href="/tutorials">Tutorials</a></li>
			<li><a href="/screencasts">Screencasts</a></li>
			<li><a href="/snippets">Snippets</a></li>
			<li><a href="/cells">Cells</a></li>
			<li><a href="/forums">Forums</a></li>
			<li><a href="/login" target="" class="logout">Login</a></li>
		</ul>

		<div class="splitter"></div>

		<div id="intro">

			<div id="about">
				<h1>
					Welcome to the Fuel Depot!
				</h1>
				<p>&nbsp;</p>
				<h1>
					This is the central repository of FuelPHP screencasts, tutorials, documentation, code snippets and Fuel Cells.
				</h1>
			</div>

			<ul id="calltos">
				<li id="discover">
					<a href="/discover" title="Discover"><span class="link">Discover</span></a>
					<span class="desc">Learn more about Fuel Depot.</span>
				</li>

				<li id="contribute">
					<a href="/contribute" title="Contribute"><span class="link">Contribute</span></a>
					<span class="desc">Be part of it. Read it. Use it. Improve it.!</span>
				</li>

				<li id="cells">
					<a href="/cells" title="Cells"><span class="link">Fuel Cells</span></a>
					<span class="desc">Boost the power of your application!</span>
				</li>
			</ul>

		</div>

	</div>

	<div id="midbar">

		<div id="twitter">
			<?php echo \Html::img(\Theme::instance()->asset('img/twitter_bird.png'));?>
			<ul id="twitter_update_list">
				<li>&nbsp;</li>
			</ul>
		</div>
	</div>

</div>

<div id="contentWrapper">

	<div id="content">

		<div class="page">

			<div class="page-chunk default">

				<div class="one_third">
					<h4>What exactly is Fuel Depot?</h4>
					<p>
						Fuel Depot supplies the web application developer with everything needed to create awesome applications using FuelPHP!
					</p>
					<p>
						In the depot you will find the online documentation of both the current release of FuelPHP and of the latest development version.
						You will have access to the complete API documentation of all classes, video and text tutorials, code snippets and other user contributions.
					</p>
				</div>

				<div class="one_third">
					<h4>Use the Cells Luke...</h4>
					<p>
						FuelPHP introduces the concept of Fuel Cells.
					</p>
					<p>
						Cells are installable packages, which you can install manually or using the <strong>oil</strong> commandline tool.
					</p>
					<p>
						With Cells you can quickly add functionality to your FuelPHP installation, which will enable you to create applications
						rich in functionality very quickly.
					</p>
					<p>
						To see what Cells are available, browse the <a href="/cells">online Cells repository</a>.
					</p>
				</div>

				<div class="one_third_last">
					<h4>Join the community!</h4>
					<p>
						Whether you're an experienced PHP developer or a newcomer just getting started, there are many ways for you to participate
						in the FuelPHP project.
					</p>
					<p>
						Help improving the documentation, post your code snippets, tutorials or screencasts.
						Publish your Cells in your central Cells repository. Join the forum to get help or help others with FuelPHP.
					</p>
					<p>
						Whatever you do, becoming an active member of the FuelPHP community is your step to creating something amazing.
					</p>
				</div>

			</div>

		</div>

	</div>

</div>

<div id="footerWrapper">

	<div id="footer">

		<div class="one_quarter">
			<h6>Getting Started</h6>

			<ul>
				<li><a href="http://docs.fuelphp.com/">Documentation</a></li>
				<li><a href="/tutorials">Screencasts</a></li>
				<li><a href="/tutorials">Tutorials</a></li>
				<li><a href="/forums">Forums</a></li>
			</ul>
		</div>

		<div class="one_quarter">
			<h6>About</h6>

			<ul>
				<li><a href="/about">The Depot Masters</a></li>
				<li><a href="http://fuelphp.com/about">The FuelPHP Team</a></li>
				<li><a href="http://fuelphp.com/contact">Contact Us</a></li>
			</ul>
		</div>

		<div class="one_quarter">
			<h6>Fuel Haiku</h6>
			<p>Simple, Powerful<br />Write web applications fast<br />Happiness ensues</p>
		</div>

		<div class="one_quarter_last">
			<!--
			<a href="http://www.pagodabox.com/" target="_blank">
				<div id="pagoda"><img src="/addons/default/themes/fuel/img/pagoda-float.png" alt="pagoda-float" /></div>
			</a>
			-->
		</div>

	</div>

</div>

<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://api.twitter.com/1/statuses/user_timeline/fuelphpdepot.json?callback=twitterCallback2&amp;count=1&amp;include_rts=true"></script>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.4.2');</script>

<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/cufon.js'); ?>"></script>
<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/bebas.font.js'); ?>"></script>
<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/ui-core.js'); ?>"></script>
<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/validate.js'); ?>"></script>

<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/localscroll.js'); ?>"></script>
<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/scrollto.js'); ?>"></script>
<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/easing.js'); ?>"></script>
<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/colorbox.js'); ?>"></script>
<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/tipsy.js'); ?>"></script>
<script type="text/javascript" src="<?php echo \Theme::instance()->asset('js/global.js'); ?>"></script>

<script type="text/javascript">Cufon.now();</script>
<script type="text/javascript">
  function recordOutboundLink(link, category, action) {
    _gat._getTrackerByName()._trackEvent(category, action);
    setTimeout('document.location = "' + link.href + '"', 100);
  }
</script>

</body>
</html>
