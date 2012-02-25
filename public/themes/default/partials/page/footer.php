<div id="footerWrapper">

	<div id="footer">

		<div class="one_quarter">
			<h6>Getting Started</h6>

			<ul>
				<li><a href="/documentation">Documentation</a></li>
				<li><a href="/screencasts">Screencasts</a></li>
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
<script type="text/javascript" src="http://api.twitter.com/1/statuses/user_timeline/fuelphp.json?callback=twitterCallback2&amp;count=1&amp;include_rts=true"></script>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.7.1');</script>

<?php \Theme::instance()->asset->js(array('ui-core.js', 'validate.js', 'localscroll.js', 'easing.js', 'colorbox.js', 'tipsy.js', 'global.js'), array(), 'footer'); ?>
<?php echo \Theme::instance()->asset->render('footer'); ?>
