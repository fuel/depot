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
			<h6>Hosted by</h6>

			<a href="http://www.exite.eu/en/services/hosting.html" style="" target="_blank">
				<div id="exite">
					<?php echo \Theme::instance()->asset->img('exite.png', array('width' => 125, 'style' => 'margin: 10px 0px 0px 8px'));?>
					<p style="margin:5px 0px 0px 13px;font-size:8px;text-transform:none">Expert in information technology</p>
				</div>
			</a>
		</div>

	</div>

</div>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.7.1');</script>
<script type="text/javascript">google.load('jqueryui', '1.8.17');</script>

<?php \Theme::instance()->asset->js(array('global.js'), array(), 'footer'); ?>
<?php echo \Theme::instance()->asset->render('footer'); ?>
