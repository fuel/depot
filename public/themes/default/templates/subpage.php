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

<div id="subheaderWrapper">
	<div id="subheader">

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

	</div>

</div>

<div id="contentWrapper">

	<div id="content"><?php if (isset($content)) echo $content; ?></div>

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
